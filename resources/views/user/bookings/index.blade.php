@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0">
            <i class="bi bi-receipt me-2 text-main"></i>&nbsp;My Bookings
        </h1>
    </div>
@endsection



@section('main-body')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="fs-5 m-0">Search Parking Lot</h2>
            </div>
            <form action="{{ route('my-bookings') }}" method="get">
                @if (session()->has('profile-update-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-update-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="booking_id" class="form-label">Booking ID</label>

                                    <input type="text" class="form-control" name="booking"
                                        placeholder="Search booking id" value="{{request()->get('booking')}}">
                                    @error('booking_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="booking_status" class="form-label">Booking Status</label>

                                    <select name="booking_status" id="booking_status" class="form-control">
                                        @php $status = request()->get("booking_status", "10") @endphp
                                        <option value="-10" @if ($status == '-10') selected @endif>Any</option>
                                        <option value="10" @if ($status == '10') selected @endif>Pending
                                        </option>
                                        <option value="20" @if ($status == '20') selected @endif>On Going
                                        </option>
                                        <option value="30" @if ($status == '30') selected @endif>Finished
                                        </option>
                                        <option value="40" @if ($status == '40') selected @endif>Cancelled
                                        </option>
                                    </select>

                                    @error('booking_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('my-bookings') }}" class="btn btn-light me-2"><i
                                class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i></i>Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container">
            <div class="p-0 mt-4">
                @if ($bookings->count() > 0)
                    <div class="accordion " id="accordionExample">
                        @foreach ($bookings as $booking)
                            <div class="accordion-item mb-2 ">
                                <h2 class="accordion-header" id="booking-{{ $booking->id }}">
                                    <div class="accordion-button bg-white" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collaps-{{ $booking->id }}" aria-expanded="false"
                                        aria-controls="collaps-{{ $booking->id }}">


                                        <div class="container">
                                            <div class="row">
                                                <div
                                                    class="col-6 col-xl-2 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark text-dark">ID</span>
                                                    <p class="m-0 text-dark mt-1">#{{ $booking->id }}</p>
                                                </div>

                                                <div
                                                    class="col-6 col-xl-4 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark">Branch</span>
                                                    <p class="m-0 mt-1 text-dark">{{ $booking->Branch->name }}</p>
                                                </div>



                                                <div
                                                    class="col-6 col-xl-4 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark">Estimated Fee</span>
                                                    <p class="m-0 text-dark mt-1">
                                                        Rs {{ number_format($booking->estimatedFee(), 2, '.', '') }}
                                                        (Allocated) </p>
                                                </div>


                                                <div
                                                    class="col-6 col-xl-2 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start ml-auto">
                                                    <span class="fs-6 fw-light text-dark">Status</span>
                                                    @if ($booking->isPending())
                                                        <p class="badge bg-info m-0 mt-1">Pending</p>
                                                    @elseif ($booking->isOnGoing())
                                                        <p class="badge bg-info m-0 mt-1">On Going</p>
                                                    @elseif($booking->isFinished())
                                                        <p class="badge bg-success m-0 mt-1">Finished</p>
                                                    @elseif($booking->isCancelled())
                                                        <p class="badge bg-danger m-0 mt-1">Cancelled</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </h2>
                                <div id="collaps-{{ $booking->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="booking-{{ $booking->id }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="container">
                                            @if (Auth::user()->isSuperAdminAccount())
                                                <div class="row mb-3 border-bottom mb-4 pb-4">
                                                    <div class="col-6 text-wrap">
                                                        <span class="fs-6 fw-light text-dark text-break">Branch</span>
                                                        <p class="m-0 text-dark mt-1 text-break">
                                                            <a href="{{ route('branches-management.view', ['branch' => $booking->Branch->id]) }}"
                                                                class="text-dark text-decoration-none"
                                                                target="_blank">{{ $booking->Branch->name }}
                                                                ({{ $booking->Branch->City->name }})
                                                            </a>
                                                        </p>
                                                    </div>
                                                    <div class="col-6 text-wrap">
                                                        <span class="fs-6 fw-light text-dark text-break">Hourly Rate</span>
                                                        <p class="m-0 text-dark mt-1 text-break">Rs
                                                            {{ $booking->hourly_rate }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                @if ($booking->isPending() || $booking->isCancelled())
                                                    <div class="col-12 col-sm-3 mb-xl-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Starts At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>

                                                    <div class="col-12 col-sm-3 mb-xl-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Ends At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>
                                                @elseif ($booking->isOnGoing())
                                                    <div class="col-12 col-sm-3 mb-xl-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Starts At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="col-12 col-sm-3  mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Ends At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>
                                                @elseif ($booking->isFinished())
                                                    <div
                                                        class="col-12 col-sm-3  mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Starts At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>

                                                    <div
                                                        class="col-12 col-sm-3  mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                        <span class="fs-6 fw-light text-dark">Ends At</span>
                                                        <p class="m-0">
                                                            {{ $booking->estimated_start_time->format('Y-m-d H:i A') }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <div class="col-12 col-sm-3"></div>

                                                <div class="col-12 col-sm-3 mb-xl-0 d-flex flex-column align-items-end">

                                                    <a href="{{ route('my-bookings.view', ['booking' => $booking->id]) }}"
                                                        target="_blank" class="btn btn-light"><i
                                                            class="bi bi-arrow-up-right-circle-fill me-2"></i></i>View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="card d-flex justify-content-center align-items-center flex-column py-5">
                        <img src="{{ asset('images/ilustrations/feeling-blue.svg') }}" class="mb-4" alt="Empty Image"
                            style="width:220px">
                        <h3>Mmmm...</h3>
                        <p class="m-0">You have not reserved a parking space yet.</p>
                    </div>
                @endif
            </div>
        </div>
    @endsection
