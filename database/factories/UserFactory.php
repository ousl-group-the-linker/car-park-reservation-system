<?php

namespace Database\Factories;

use App\Models\SriLankaCity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'address_line_1' => $this->faker->streetSuffix(),
            'address_line_2' => $this->faker->streetName(),
            'address_line_3' => null,
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0" . $this->faker->randomNumber(2) . $this->faker->randomNumber(7),
            'password' => Hash::make("1234567890"),
            // 'work_for_branch_id' =>
            'is_activated' => true
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function role($role)
    {
        return $this->state(function (array $attributes) use ($role) {
            return [
                'role' => $role,
            ];
        });
    }
}
