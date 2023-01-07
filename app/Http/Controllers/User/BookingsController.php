<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{

    public function index(Request $request)
    {
        $bookingStatus = $request->input("booking_status", 10);
        $bookingId = $request->input("booking", null);

        $bookings = Auth::user()
            ->Bookings();

        if (strlen($bookingId) > 0) {
            $bookings->where("id", $bookingId);
        }

        if ($bookingStatus == 10) {
            $bookings->where("status", Booking::STATUS_PENDING);
        } elseif ($bookingStatus == 20) {
            $bookings->where("status", Booking::STATUS_ONGOING);
        } elseif ($bookingStatus == 30) {
            $bookings->where("status", Booking::STATUS_FINISHED);
        } elseif ($bookingStatus == 40) {
            $bookings->where("status", Booking::STATUS_CANCELLED);
        }

        $bookings = $bookings->orderBy("created_at", "DESC")
            ->paginate(15);

        return view("user.bookings.index", [
            "bookings" => $bookings
        ]);
    }


    public function new(Request $request)
    {
        $branch = Branch::find($request->input("branch"));

        if (!$branch) return redirect()->route('find-parking-lot');

        return view("user.bookings.new", [
            "branch" => $branch
        ]);
    }
    public function placeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "branch_id" => "required|exists:branches,id",
            "arrivel_date" => "required|date_format:Y-m-d",
            "arrivel_time" => "required|date_format:H:i",
            "release_date" => "required|date_format:Y-m-d",
            "release_time" => "required|date_format:H:i",
            "i_agree" => "required|accepted"
        ], [
            "i_agree.*" => "You must accept the terms and conditions."
        ]);

        $validator->after(function ($validator) {
            if ($validator->errors()->count() > 0) return;

            $data = (object)$validator->getData();
            $startDate = Carbon::createFromFormat("Y-m-d",  $data->arrivel_date);
            $startDateTime = Carbon::createFromFormat("Y-m-d H:i",  "{$data->arrivel_date} {$data->arrivel_time}");

            $releaseDate = Carbon::createFromFormat("Y-m-d",  $data->release_date);
            $releaseDateTime = Carbon::createFromFormat("Y-m-d H:i",  "{$data->release_date} {$data->release_time}");

            if (!($startDate->isAfter(Carbon::now()) || $startDate->isToday())) {
                $validator->errors()->add("arrivel_date", "The arrival date should be a date after or the same day as the current date.");
            }

            if (!($startDateTime->isAfter(Carbon::now()) || $startDateTime->isToday())) {
                $validator->errors()->add("arrivel_time", "The arrivel time should be a time after the current time.");
            }

            if (!($releaseDate->isAfter($startDate) || $releaseDate->equalTo($startDate))) {
                $validator->errors()->add("release_date", "The release date should be after arrival.");
            }
            if (!($releaseDateTime->isAfter($startDateTime) || $releaseDateTime->equalTo($startDateTime))) {
                $validator->errors()->add("release_time", "The release time should be after arrival.");
            }
        });

        if ($validator->fails()) {
            return redirect()->route("my-bookings.new", ["branch" => $validator->validated()["branch_id" ?? null]])
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $data = (object)$validator->validated();

        $branch = Branch::findorfail($data->branch_id);

        $startDateTime = Carbon::createFromFormat("Y-m-d H:i",  "{$data->arrivel_date} {$data->arrivel_time}");
        $releaseDateTime = Carbon::createFromFormat("Y-m-d H:i",  "{$data->release_date} {$data->release_time}");

        $booking = Booking::create([
            "client_id" => Auth::user()->id,
            "branch_id" => $branch->id,
            "estimated_start_time" => $startDateTime->format("Y-m-d H:i"),
            "estimated_end_time" => $releaseDateTime->format("Y-m-d H:i"),
            "status" => Booking::STATUS_PENDING,
            "hourly_rate" => $branch->hourly_rate
        ]);

        return redirect()->route("my-bookings.view", ["booking" => $booking->id])
            ->with(["message" => "The booking has been successfully made."]);
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

        return view("user.bookings.booking", [
            "booking" => $booking,
            "data" => $data
        ]);
    }

    public function markAsCancelled(Booking $booking, Request $request)
    {
        $this->authorize("markAsCancelled", $booking);

        $booking->status = Booking::STATUS_CANCELLED;
        $booking->save();

        return redirect()->route('my-bookings.view', ["booking" => $booking->id])
            ->with(["message" => "The booking is successfully cancelled."]);
    }
}
