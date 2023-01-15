<?php

namespace tests\Unit\TransactionsManagementTest\TransactionTest;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use App\Models\SriLankaDistrict;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function testIsShowChargeFormWorks(){
        Auth::login(User::factory()->role(User::$ROLE_USER)->create());
        $districts = SriLankaDistrict::all();
        $response = $this->get(route("balance-and-recharge.recharge"), [
            "districts" => $districts, 
        ]);
        $response->assertStatus(200);
    }

    public function testIsConfirmRechargeWorks(){
        Auth::login(User::factory()->role(User::$ROLE_USER)->create());
        
        $response = $this->post(route("balance-and-recharge.recharge.confirm"), [
            "first_name" => $this->faker()->firstName,
            "last_name" =>  $this->faker()->lastName,
            "email" =>  $this->faker()->safeEmail,
            "address_line_1" => $this->faker()->streetAddress,
            "address_line_2" => $this->faker()->streetName,
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "amount" => "500"
        ]);
        $response->assertStatus(200);
    }

    public function testIsTransactionPageLoads()
    {
        Auth::login(User::factory()->role(User::$ROLE_USER)->create());

        $response = $this->get(route("balance-and-recharge.transactions"));
        $response->assertStatus(200);
    }

    public function testIsTransactionHoldsPageLoads()
    {
        Auth::login(User::factory()->role(User::$ROLE_USER)->create());

        $response = $this->get(route("balance-and-recharge.holds"));
        $response->assertStatus(200);
    }
}