<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SriLankaCity;
use App\Models\SriLankaDistrict;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{

    public function allTransactions(Request $request)
    {

        $transactions = Auth::user()
            ->Transactions()
            ->notStatus(Transaction::$STATUS_NONE)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->status(Transaction::$STATUS_SUCCESS);
                });
                $query->orWhere(function ($query) {
                    $query->status(Transaction::$STATUS_FAILED);
                });
                $query->orWhere(function ($query) {
                    $query->status(Transaction::$STATUS_REFUNDED);
                });
            });

        $transactions = $transactions->orderBy("created_at", "DESC")
            ->paginate(15);

        $accountSummery = $this->accountSummery();

        return view("user.balance-and-recharge.transactions", [
            "transactions" => $transactions,
            "accountSummery" => $accountSummery
        ]);
    }

    public function allHolds(Request $request)
    {
        $transactions = Auth::user()
            ->Transactions()
            ->status(Transaction::$STATUS_PENDING);

        $transactions = $transactions->orderBy("created_at", "DESC")
            ->paginate(15);

        $accountSummery = $this->accountSummery();

        return view("user.balance-and-recharge.holds", [
            "transactions" => $transactions,
            "accountSummery" => $accountSummery
        ]);
    }


    public function showRechargeForm(Request $request)
    {
        $districts = SriLankaDistrict::all();
        return view("user.balance-and-recharge.recharge.recharge", [
            'districts' => $districts
        ]);
    }
    public function confirmRecharge(Request $request)
    {
        $data = (object)$request->validate([
            "first_name" => "required|string",
            "last_name" =>  "required|string",
            "email" =>  "required|string|email",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "contact_number" => "required|numeric|digits:10",
            "amount" => "required|numeric|min:50"
        ]);

        $data->address_city = SriLankaCity::findorfail($data->address_city);

        $transaction = Auth::user()->Transactions()->create([
            'amount' => $data->amount,
            'intent' => Transaction::$INTENT_RELOAD,
        ])->fresh();

        $transaction->PayherePayment()->create([
            'payhere_amount' => $data->amount
        ]);

        $hash = strtoupper(
            md5(
                config('payhere.merchant_id') .
                    $transaction->reference_id .
                    number_format($data->amount, 2, '.', '') .
                    config("payhere.currency") .
                    strtoupper(md5(config('payhere.merchant_secret')))
            )
        );

        return view("user.balance-and-recharge.recharge.confirm-recharge", [
            'data' => $data,
            'hash' => $hash,
            'transaction' => $transaction
        ]);
    }


    public function payherePaymentWebhook(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'merchant_id' => "required|string",
            'order_id' => "required|string|exists:transactions,reference_id",
            'payment_id' =>  "required|string",
            'captured_amount' => "required|numeric",
            'payhere_amount' => "required|numeric",
            'payhere_currency' => "required|string",
            'status_code' =>  "required|numeric",
            'md5sig' => "required|string",
            'status_message' => "nullable|string",
            'method' => "nullable|string",
            'card_holder_name' => "nullable|string",
            'card_no' => "nullable|string",
            'card_expiry' => "nullable|string"
        ]);

        if ($validator->fails()) {
            Log::error($validator->errors());
            abort(402);
        }

        $data = (object)$validator->validated();

        $transaction = Transaction::where("reference_id", $data->order_id)->first();

        $payment = $transaction->PayherePayment;
        $payment->gateway_payment_id = $data->payment_id;
        $payment->payhere_amount = $data->payhere_amount;
        $payment->status_code = $data->status_code;
        $payment->method = $data->method;
        $payment->status_message = $data->status_message;
        $payment->card_holder_name = $data->card_holder_name;
        $payment->card_no = $data->card_no;
        $payment->card_expiry = $data->card_expiry;

        $transaction->status = Transaction::$STATUS_SUCCESS;
        $transaction->save();

        $payment->save();

        return response(null, 200);
    }
    public function paymentStatusPage(Request $request)
    {
        $orderId = $request->input("order_id");
        $transaction = Transaction::where("reference_id", $orderId)->first();
        if (!$transaction) abort(404);

        return view("user.balance-and-recharge.recharge.payment-status", [
            "transaction" => $transaction
        ]);
    }

    private function accountSummery()
    {
        $data = (object)[];

        $data->onHold = abs(Auth::user()->OnHoldBalance());

        $data->currentBalance = Auth::user()->CurrentBalance();

        $data->availableBalance = Auth::user()->AvailableBalance();


        return $data;
    }
}
