<?php

namespace tests\Unit\BookingsManagementTest\BookingsManagementTest;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Booking;

class BokkingsManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIsBookingsDashboardShows(){
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());
        $response = $this->get(route("bookings-management"));
        $response->assertStatus(200);
    }

    public function testIsBookingManagementWorksCorrectly(){
        Auth::login(User::factory()->role(User::$ROLE_SUPER_ADMIN)->create());

        $email = $this->faker()->safeEmail;
        $branch = Branch::factory()->create();
        //Booking::factory()->create();
        //$booking_id=Booking::all()->random()->id;

        $response = $this->get(route("bookings-management"), [
            "email" => $email,
            "booking_id" =>10,//$booking_id,
            "branch"=>$branch,
            "status"=>Booking::STATUSES,
        ]);
        $response->assertStatus(200);
    }
}
