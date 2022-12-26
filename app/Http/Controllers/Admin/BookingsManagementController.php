<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use Illuminate\Http\Request;

class BookingsManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $data = (object)$request->validate([
            "booking_id" => "nullable|string",
            "branch" => "nullable|string",
            "email" => "nullable|string",
            "status" => "nullable|string",
        ]);

        $bookings = Booking::query();

        if (($data->status ?? NULL) != null) {
            $bookings->where("status", $data->status);
        }
        if (($data->branch ?? NULL) != null) {
            $bookings->whereHas("Branch", function ($query) use ($data) {
                $query->where("id", trim($data->branch));
            });
        }
        if (($data->email ?? NULL) != null) {
            $bookings->whereHas("Client", function ($query) use ($data) {
                $query->where("email", trim($data->email));
            });
        }
        if (($data->booking_id ?? NULL) != null) {
            $bookings->where("id", "like", "%" . $data->booking_id . "%");
        }

        $bookings = $bookings->orderBy("created_at", "DESC")
            ->paginate(20);

        $branches = Branch::all();
        $statuses = Booking::STATUSES;

        return view("admin.bookings-management.index", [
            "bookings" => $bookings,
            "branches" => $branches,
            "statuses" => $statuses,
        ]);
    }
}
