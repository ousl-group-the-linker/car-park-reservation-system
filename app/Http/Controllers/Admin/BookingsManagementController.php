<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingsManagementController extends Controller
{
    public function index(Request $request)
    {

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

        if (Auth::user()->isManagerAccount()) {
            $bookings->where("branch_id", Auth::user()->ManageBranch->id);
        } else if (Auth::user()->isCounterAccount()) {
            $bookings->where("branch_id", Auth::user()->WorkForBranch->id);
        }

        $bookings = $bookings->orderBy("created_at", "DESC")->paginate(20);

        $branches = Branch::all();
        $statuses = Booking::STATUSES;

        return view("admin.bookings-management.index", [
            "bookings" => $bookings,
            "branches" => $branches,
            "statuses" => $statuses,
        ]);
    }
    public function view(Booking $booking, Request $request)
    {
        $data = (object)[];

        $data->estimate = (object)[];
        $data->estimate->start_at = $booking->estimated_start_time;
        $data->estimate->end_at = $booking->estimated_end_time;
        $data->estimate->hours = max(1, $data->estimate->end_at->diffInHours($data->estimate->start_at));
        $data->estimate->fee = round(bcmul($booking->hourly_rate,  $data->estimate->hours, 4), 2);


        if ($booking->isOnGoing()) {
            $data->actual = (object)[];
            $data->actual->start_at = $booking->real_start_time;
            $data->actual->end_at = Carbon::now();
            $data->actual->hours = max(1, $data->actual->end_at->diffInHours($data->actual->start_at));
            $data->actual->fee = round(bcmul($booking->hourly_rate, $data->actual->hours, 4), 2);
        } else if ($booking->isFinished()) {
            $data->actual = (object)[];
            $data->actual->start_at = $booking->real_start_time;
            $data->actual->end_at = $booking->real_end_time;

            $data->actual->hours = max(1, $data->actual->end_at->diffInHours($data->actual->start_at));
            $data->actual->fee = round(bcmul($booking->hourly_rate, $data->actual->hours, 4), 2);
        }
        return view("admin.bookings-management.booking", [
            "booking" => $booking,
            "data" => $data
        ]);
    }
    public function markAsOnGoing(Booking $booking, Request $request)
    {
        $this->authorize("markAsOnGoing", $booking);

        $booking->status = Booking::STATUS_ONGOING;
        $booking->real_start_time = Carbon::now();
        $booking->save();

        return redirect()->route("bookings-management.view", ["booking" => $booking->id])
            ->with(["message" => "The booking is successfully started."]);
    }
    public function markAsCancelled(Booking $booking, Request $request)
    {
        $this->authorize("markAsCancelled", $booking);

        $booking->status = Booking::STATUS_CANCELLED;
        $booking->save();

        return redirect()->route("bookings-management.view", ["booking" => $booking->id])
            ->with(["message" => "The booking is successfully cancelled."]);
    }
    public function markAsFinished(Booking $booking, Request $request)
    {
        $this->authorize("markAsFinished", $booking);

        $booking->status = Booking::STATUS_FINISHED;
        $booking->real_end_time = Carbon::now();
        $booking->save();

        return redirect()->route("bookings-management.view", ["booking" => $booking->id])
            ->with(["message" => "The booking is successfully finished."]);
    }
}
