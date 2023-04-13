<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     *
     * @param  \App\Models\Booking  $booking
     * @return void
     */
    public function created(Booking $booking)
    {
        $booking->reference_id = "B-" . ($booking->id + 675457);
        $booking->saveQuietly();
    }

}
