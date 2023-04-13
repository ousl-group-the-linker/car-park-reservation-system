<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            "client_id" => User::all()->random()->id,
            "branch_id" => Branch::all()->random()->id,
            // "estimated_start_time" => $startTime,
            // "estimated_end_time",
            // "real_start_time",
            // "real_end_time",
            "hourly_rate" => $this->faker->randomFloat(null, 1, 10),
            // "status",
        ];
    }

    /**
     * Set status of the booking
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function Status($status)
    {
        return $this->state(function (array $attributes) use ($status) {
            $startTime = Carbon::now()->addHours(random_int(-20, +20));

            if ($status == Booking::STATUS_PENDING) {
                return [
                    "estimated_start_time" => (clone $startTime),
                    "estimated_end_time" => (clone $startTime)->addHours(random_int(0, +5)),
                    "status" => Booking::STATUS_PENDING,
                ];
            } else if ($status == Booking::STATUS_CANCELLED) {
                return [
                    "estimated_start_time" => (clone $startTime),
                    "estimated_end_time" => (clone $startTime)->addHours(random_int(0, +5)),
                    "status" => Booking::STATUS_CANCELLED,
                ];
            } else if ($status == Booking::STATUS_ONGOING) {
                return [
                    "estimated_start_time" => (clone $startTime),
                    "estimated_end_time" => (clone $startTime)->addHours(random_int(0, +5)),

                    "real_start_time" => ($startTime)->addHours(random_int(0, +5)),

                    "status" => Booking::STATUS_ONGOING,
                ];
            } else if ($status == Booking::STATUS_FINISHED) {
                return [
                    "estimated_start_time" => $startTime,
                    "estimated_end_time" => (clone $startTime)->addHours(random_int(0, +5)),

                    "real_start_time" => (clone $startTime)->addHours(random_int(0, +5)),
                    "real_end_time" => (clone $startTime)->addHours(random_int(0, +5)),

                    "status" => Booking::STATUS_FINISHED,
                ];
            }
        });
    }
}
