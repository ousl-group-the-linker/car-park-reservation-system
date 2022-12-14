<?php

namespace Database\Seeders;

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
        User::factory(10)->role(User::$ROLE_SUPER_ADMIN)->create();
        User::factory(10)->role(User::$ROLE_MANAGER)->create();
        User::factory(10)->role(User::$ROLE_COUNTER)->create();
        User::factory(100)->role(User::$ROLE_USER)->create();
    }
}
