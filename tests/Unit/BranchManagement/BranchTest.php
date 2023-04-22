<?php

namespace Tests\Unit\BranchManagement\BranchTest;
use Tests\TestCase;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use App\Models\SriLankaDistrict;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Branch;

class BranchTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIsBranchManagementPageLoads()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $response = $this->get(route("branches-management.view"));
        $response->assertStatus(200);
    }

    public function testCreateNewBranchWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $response = $this->post(route("branches-management.new"), [
            "name" =>"uphill",
            "address_line_1" => $this->faker()->streetAddress,
            "address_line_2" => $this->faker()->streetName,
            "address_line_3" => "",
            "email" =>  $this->faker()->safeEmail,
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "parking_slots" => "500",
            "manager_id"=>"80",
            "hourly_rate"=>"50"
        ]);
        $response = $this->post(route("branches-management.new",));
        $response->assertStatus(302);
       
    }

    

}
