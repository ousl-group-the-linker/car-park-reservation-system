<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Transaction;
use App\Services\SmsDispatcherService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingsManagementController extends Controller
{
    /**
     * Show all bookings
     */
    public function index(Request $request)
    {

        $data = (object)$request->validate([
            "booking_id" => "nullable|string",
            "branch" => "nullable|string",
            "email" => "nullable|string",
            "status" => "nullable|string",
        ]);

        $bookings = Booking::query();

        if (($data->status ?? NULL) != null) {
            $bookings->where("status", $data->status);
        }
        if (($data->branch ?? NULL) != null) {
            $bookings->whereHas("Branch", function ($query) use ($data) {
                $query->where("id", trim($data->branch));
            });
        }
        if (($data->email ?? NULL) != null) {
            $bookings->whereHas("Client", function ($query) use ($data) {
                $query->where("email", trim($data->email));
            });
        }
        if (($data->booking_id ?? NULL) != null) {
            $bookings->where("reference_id", "=", $data->booking_id);
        }

        if (Auth::user()->isManagerAccount()) {
            $bookings->where("branch_id", Auth::user()->ManageBranch->id);
        } else if (Auth::user()->isCounterAccount()) {
            $bookings->where("branch_id", Auth::user()->WorkForBranch->id);
        }

        $bookings = $bookings->orderBy("created_at", "DESC")->paginate(20);

        $branches = Branch::all();
        $statuses = Booking::STATUSES;

        return view("admin.bookings-management.index", [
            "bookings" => $bookings,
            "branches" => $branches,
            "statuses" => $statuses,
        ]);
    }

    /**
     * Show the given booking
     */
    public function view(Booking $booking, Request $request)
    {
        $data = (object)[];

        $data->vehicle_no = $booking->vehicle_no;
        $data->estimate = (object)[];
        $data->estimate->start_at = $booking->estimated_start_time;
        $data->estimate->end_at = $booking->estimated_end_time;
        $data->estimate->hours = max(1, $data->estimate->end_at->diffInHours($data->estimate->start_at));
        $data->estimate->fee = round(bcmul($booking->hourly_rate,  $data->estimate->hours, 4), 2);


        if ($booking->isOnGoing()) {
            $data->actual = (object)[];
            $data->actual->start_at = $booking->real_start_time;
            $data->actual->end_at = Carbon::now();
            $data->actual->hours = max(1, $data->actual->end_at->diffInHours($data->actual->start_at));
            $data->actual->fee = round(bcmul($booking->hourly_rate, $data->actual->hours, 4), 2);
        } else if ($booking->isFinished()) {
            $data->actual = (object)[];
            $data->actual->start_at = $booking->real_start_time;
            $data->actual->end_at = $booking->real_end_time;

            $data->actual->hours = max(1, $data->actual->end_at->diffInHours($data->actual->start_at));
            $data->actual->fee = round(bcmul($booking->hourly_rate, $data->actual->hours, 4), 2);
        }
        return view("admin.bookings-management.booking", [
            "booking" => $booking,
            "data" => $data
        ]);
    }

    /**
     * Change the given booking's status to ongoing.
     */
    public function markAsOnGoing(Booking $booking, Request $request)
    {
        $this->authorize("markAsOnGoing", $booking);

        try {
            DB::beginTransaction();
            $booking->status = Booking::STATUS_ONGOING;
            $booking->real_start_time = Carbon::now();
            $booking->save();

            $branch = $booking->Branch;
            $user = $booking->Client;

            $message = "Your vehicle parking reservation (Reference No: {$booking->reference_id}) is started now at {$branch->name}.";
            (new SmsDispatcherService())->send($user->contact_number, $message);

            DB::commit();

            return redirect()->route("bookings-management.view", ["booking" => $booking->reference_id])
                ->with(["message" => "The booking is successfully started."]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            abort(500, "Something was wrong, please try again.");
        }
    }


    /**
     * Change the given booking's status to cancelled
     */
    public function markAsCancelled(Booking $booking, Request $request)
    {
        $this->authorize("markAsCancelled", $booking);

        try {
            DB::beginTransaction();
            $booking->status = Booking::STATUS_CANCELLED;
            $booking->save();

            //refund allocated amount
            $booking->Transactions()->update([
                "status" => Transaction::$STATUS_REFUNDED,
            ]);
            $user = $booking->Client;
            $branch = $booking->Branch;
            $message = "Your vehicle parking reservation (Reference No: {$booking->reference_id}) is cancelled at {$branch->name}.";

            (new SmsDispatcherService())->send($user->contact_number, $message);

            DB::commit();

            return redirect()->route("bookings-management.view", ["booking" => $booking->reference_id])
                ->with(["message" => "The booking is successfully cancelled."]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            abort(500, "Something was wrong, please try again.");
        }
    }

    /**
     * Change the given booking's status to finished
     */
    public function markAsFinished(Booking $booking, Request $request)
    {
        $this->authorize("markAsFinished", $booking);

        try {
            DB::beginTransaction();

            $booking->status = Booking::STATUS_FINISHED;
            $booking->real_end_time = Carbon::now();
            $booking->save();

            //charge the allocated & estimated amount.
            foreach ($booking->Transactions as $transaction) {
                $transaction->status = Transaction::$STATUS_SUCCESS;
                $transaction->save();
            }


            $allocatedAmount = abs($booking->Transactions()
                ->status(Transaction::$STATUS_SUCCESS)
                ->intent(Transaction::$INTENT_BOOKING)
                ->get()
                ->sum("amount") ?? 0);
            $realAmount = $booking->hourly_rate * max(1, $booking->real_end_time->diffInHours($booking->real_start_time));

            //create a new transaction for settle the excess amount from user.
            $extraAmount = $allocatedAmount - $realAmount;
            if (abs($extraAmount) > 0) {
                $booking->Transactions()->create([
                    "client_id" => $booking->Client->id,
                    'amount' => $extraAmount,
                    'status' => Transaction::$STATUS_SUCCESS,
                    'intent' => Transaction::$INTENT_BOOKING,
                ]);
            }
            $user = $booking->Client;
            $branch = $booking->Branch;
            $message = "Your vehicle parking reservation (Reference No: {$booking->reference_id}) is finished at {$branch->name}.";

            (new SmsDispatcherService())->send($user->contact_number, $message);

            DB::commit();

            return redirect()->route("bookings-management.view", ["booking" => $booking->reference_id])
                ->with(["message" => "The booking is successfully finished."]);
        } catch (Exception $e) {
            DB::rollBack();
            abort(500, "Something was wrong, please try again.");
        }
    }
}
