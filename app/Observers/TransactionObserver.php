<?php

namespace App\Observers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        $transaction->reference_id = "T-" . ($transaction->id + 3025875);


        if (
            $transaction->final_balance !== null
            && $transaction->status == Transaction::$STATUS_SUCCESS
        ) {
            $user = $transaction->Client;

            $currentBalance = $user->Transactions()
                ->whereNotIn("id", [$transaction->id])
                ->status(Transaction::$STATUS_SUCCESS)
                ->orderBy("id", "DESC")
                ->first()->final_balance ?? 0;

            $transaction->final_balance = $currentBalance + $transaction->amount;

            $transaction->saveQuietly();
        }


        $transaction->save();
    }

    /**
     * Handle the Transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        if (
            $transaction->final_balance == null
            && $transaction->status == Transaction::$STATUS_SUCCESS
            && $transaction->isDirty("status")
        ) {
            $user = $transaction->Client;


            $currentBalance = $user->Transactions()
                ->whereNotIn("id", [$transaction->id])
                ->status(Transaction::$STATUS_SUCCESS)
                ->orderBy("id", "DESC")
                ->first()->final_balance ?? 0;


            $transaction->final_balance = $currentBalance + $transaction->amount;

            $transaction->saveQuietly();
        }
    }
}
