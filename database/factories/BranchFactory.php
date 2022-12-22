<?php

namespace Database\Factories;

use App\Models\SriLankaCity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => "Branch " . $this->faker->name(),
            "email" => $this->faker->safeEmail(),
            'address_line_1' => $this->faker->streetSuffix(),
            'address_line_2' => $this->faker->streetName(),
            'address_line_3' => null,
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0" . $this->faker->randomNumber(2) . $this->faker->randomNumber(7),
            "parking_slots" => $this->faker->numberBetween(10, 20),
            // "manager_id" => User::where("role", User::$ROLE_MANAGER)->get()->random()->id
        ];
    }
}
