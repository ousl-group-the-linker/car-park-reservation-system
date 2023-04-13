<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->role(User::$ROLE_SUPER_ADMIN)->create();

        User::factory(1)->role(User::$ROLE_MANAGER)
            ->hasManageBranch(1)
            ->create();

        User::factory(1)->role(User::$ROLE_COUNTER)
            ->hasWorkForBranch(1)
            ->create();

        User::factory(1)->role(User::$ROLE_USER)->create();


        // Booking::factory(10)->status(Booking::STATUS_PENDING)->create();
        // Booking::factory(10)->status(Booking::STATUS_CANCELLED)->create();
        // Booking::factory(10)->status(Booking::STATUS_ONGOING)->create();
        // Booking::factory(10)->status(Booking::STATUS_FINISHED)->create();

        // Transaction::factory(100)->intent(Transaction::$INTENT_RELOAD)->status(Transaction::$STATUS_PENDING)->create();
    }
}
