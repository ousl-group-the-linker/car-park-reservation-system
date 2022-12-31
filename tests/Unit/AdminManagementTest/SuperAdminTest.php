<?php

namespace Tests\Unit\AdminManagementTest;

use App\Models\Branch;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class SuperAdminTest extends TestCase
{
    /**
     * Check whether the profule infomatuin is loading
     *
     * @return void
     */
    use RefreshDatabase, WithFaker;

    public function testIsAdminProfilePageLoads()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $response = $this->get(route("super-admin.dashboard"));
        $response->assertStatus(200);
    }

    public function testIsCreateNewSuperAdminWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $email = $this->faker()->safeEmail;
        $response = $this->post(route("super-admin.admin-management.new"), [
            "email" => $email,
            "first_name" => $this->faker()->firstName,
            "last_name" => $this->faker()->lastName,
            "address_line_1" => $this->faker()->streetAddress,
            "address_line_2" => $this->faker()->streetName,
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "password" => Hash::make($this->faker()->randomNumber(8)),
            "role" => User::$ROLE_SUPER_ADMIN,
        ]);

        $response->assertStatus(302);

        $this->assertTrue(User::where("email", $email)->count() > 0);
    }
    public function testIsCreateNewMangerWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $branch = Branch::factory()->create();
        $email = $this->faker()->safeEmail;

        $response = $this->post(route("super-admin.admin-management.new"), [
            "email" => $email,
            "first_name" => $this->faker()->firstName,
            "last_name" => $this->faker()->lastName,
            "address_line_1" => $this->faker()->streetAddress,
            "address_line_2" => $this->faker()->streetName,
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "password" => Hash::make($this->faker()->randomNumber(8)),
            "role" => User::$ROLE_MANAGER,
            "branch_id" => $branch->id
        ]);

        $response->assertStatus(302);

        $this->assertTrue(User::where("email", $email)->count() > 0);
    }
    public function testIsCreateNewCounterWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $branch = Branch::factory()->create();
        $email = $this->faker()->safeEmail;

        $response = $this->post(route("super-admin.admin-management.new"), [
            "email" => $email,
            "first_name" => $this->faker()->firstName,
            "last_name" => $this->faker()->lastName,
            "address_line_1" => $this->faker()->streetAddress,
            "address_line_2" => $this->faker()->streetName,
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "password" => Hash::make($this->faker()->randomNumber(8)),
            "role" => User::$ROLE_COUNTER,
            "branch_id" => $branch->id
        ]);

        $response->assertStatus(302);

        $this->assertTrue(User::where("email", $email)->count() > 0);
    }

    public function testIsAdminSaveWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $admin = User::factory()->role(User::$ROLE_SUPER_ADMIN)->create();
        $initalFirstname = $admin->first_name;

        $response = $this->get(route("super-admin.admin-management.edit", ["admin" => $admin->id]), [
            "first_name" => "Jane",
            "last_name" => "Doe",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "role" => User::$ROLE_SUPER_ADMIN,
            "is_activated" => 1,

        ]);

        $admin->fresh();

        $response->assertStatus(200);
        $this->assertTrue($admin->first_name == $initalFirstname);
    }

    public function testIsSaveManagerAsManagerWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $password = "1234567890";
        $email =  time() . "uypo@mail.com";

        $admin = User::create([
            'first_name' => "jhon",
            'last_name' => "Doe",
            'email' => $email,
            'address_line_1' => "250",
            'address_line_2' => "hill side",
            'address_line_3' => "downtown",
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0758763147",
            'password' => Hash::make($password),
            'role' => User::$ROLE_SUPER_ADMIN
        ]);
        // $admin = User::factory()->role(User::$ROLE_MANAGER)->create();
        Branch::factory()->create();

        Auth::login($admin);


        $response = $this->get(route("super-admin.admin-management.edit", ["admin" => $admin->id]), [
            "first_name" => "Jane",
            "last_name" => "Doe",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "role" => User::$ROLE_MANAGER,
            "is_activated" => 1,
            "branch" => Branch::all()->random()->id

        ]);

        $response->assertStatus(200);
    }
    public function testIsSaveCounterAsCounterWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());


        $admin = User::factory()->role(User::$ROLE_COUNTER)->create();
        Branch::factory()->create();

        $response = $this->get(route("super-admin.admin-management.edit", ["admin" => $admin->id]), [
            "first_name" => "Jane",
            "last_name" => "Doe",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "role" => User::$ROLE_COUNTER,
            "is_activated" => 1,
            "branch" => Branch::all()->random()->id

        ]);

        $response->assertStatus(200);
    }
    public function testIsSaveManagerAsCounterWorks()
    {
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());


        $admin = User::factory()->role(User::$ROLE_MANAGER)->create();
        Branch::factory()->create();

        $response = $this->get(route("super-admin.admin-management.edit", ["admin" => $admin->id]), [
            "first_name" => "Jane",
            "last_name" => "Doe",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "role" => User::$ROLE_COUNTER,
            "is_activated" => 1,
            "branch" => Branch::all()->random()->id

        ]);

        $response->assertStatus(200);
    }
    // public function testIsSaveCounterAsManagerWorks()
    // {
    //     Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());


    //     $admin = User::factory()->role(User::$ROLE_COUNTER)->create();
    //     $branch = Branch::factory()->create();

    //     $response = $this->post(route("super-admin.admin-management.edit", ["admin" => $admin->id]), [
    //         "first_name" => "Jane",
    //         "last_name" => "Doe",
    //         "address_line_1" => "110",
    //         "address_line_2" => "nyork",
    //         "address_line_3" => "",
    //         "address_city" => SriLankaCity::all()->random()->id,
    //         "contact_number" => "0777536987",
    //         "role" => User::$ROLE_MANAGER,
    //         "is_activated" => 1,
    //         "branch_id" => $branch->id
    //     ]);

    //     $branch->fresh();
    //     $response->assertSessionDoesntHaveErrors();
    //     $response->assertStatus(302);
    //     $this->assertTrue($admin->ManageBranch->id == $branch->id);
    //     $this->assertTrue(dd($branch)->id == $admin->id);
    // }
}
