<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => User::where("role", User::$ROLE_USER)->get()->random()->id,
            'amount' => random_int(-10, 100),
            'final_balance' => random_int(-10, 100),
            // 'status' => Transaction::$STATUS_PENDING,
            // 'intent' => Transaction::$INTENT_RELOAD,
        ];
    }
    /**
     * Set status of the transaction
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function Status($status)
    {
        return $this->state(function (array $attributes) use ($status) {
            return [
                'status' => $status
            ];
        });
    }
    /**
     * Set intent of the transaction
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function Intent($intent)
    {
        return $this->state(function (array $attributes) use ($intent) {
            return [
                'intent' => $intent
            ];
        });
    }
}
