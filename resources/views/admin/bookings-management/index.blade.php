@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-receipt me-2 text-main"></i>&nbsp;Bookings Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Bookings</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="p-0" style="max-width: 780px">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Search Booking</h2>
                </div>
                <form action="{{ route('bookings-management') }}" method="get">

                    @if (session()->has('profile-update-success-message'))
                        <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                            {{ session()->get('profile-update-success-message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="booking_id" class="form-label">Booking ID</label>
                                    <input type="booking_id"
                                        class="form-control  @if ($errors->has('booking_id')) is-invalid @endif"
                                        id="booking_id" name="booking_id" placeholder="xx"
                                        value="{{ request()->input('booking_id') }}">

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="email" class="form-label">Client's Email Address</label>
                                    <input type="email"
                                        class="form-control  @if ($errors->has('email')) is-invalid @endif"
                                        id="email" name="email" placeholder="name@example.com"
                                        value="{{ request()->input('email') }}">

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="city" class="form-label">Branch</label>
                                    <select class="form-select   @if ($errors->has('branch')) is-invalid @endif"
                                        aria-label="Branch" name="branch"
                                        @if (Auth::user()->isManagerAccount() || Auth::user()->isCounterAccount()) disabled @endif>
                                        <option selected value="">Any</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                @if (request()->get('branch', Auth::user()->WorkForBranch->id ?? (Auth::user()->ManageBranch->id ?? null)) ==
                                                        $branch->id) selected @endif>{{ $branch->name }}
                                                ({{ $branch->City->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('branch') }}
                                        </div>
                                    @endif

                                </div>
                                <div class="col-6 mb-3">
                                    <label for="city" class="form-label">Status</label>
                                    <select class="form-select   @if ($errors->has('status')) is-invalid @endif"
                                        aria-label="City" name="status">
                                        <option selected value="">Any</option>
                                        @foreach ($statuses as $key => $status)
                                            <option value="{{ $key }}"
                                                @if (request()->get('status') === "$key") selected @endif> {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('bookings-management') }}" class="btn btn-light me-2"><i
                                class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i></i>Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="p-0 mt-4" style="max-width: 780px">
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
                                                <span class="fs-6 fw-light text-dark">Vehicle No</span>
                                                <p class="m-0  mt-1">
                                                    <a href="mailto:{{ $booking->vehicle_no }}"
                                                        class="text-dark text-decoration-none">{{ $booking->vehicle_no }}</a>
                                                </p>
                                            </div>



                                            <div
                                                class="col-6 col-xl-4 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                                                <span class="fs-6 fw-light text-dark">Estimated Fee</span>
                                                <p class="m-0 text-dark mt-1">
                                                    Rs {{ number_format($booking->estimatedFee(), 2, '.', '') }} </p>
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
                                                        {{ number_format($booking->hourly_rate, 2) }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            @if ($booking->isPending() || $booking->isCancelled())
                                                <div class="col-12 col-sm-4 mb-xl-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark">Client</span>
                                                    <p class="m-0">
                                                        {{ $booking->Client->email }}
                                                    </p>
                                                </div>



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
                                                <div class="col-12 col-sm-4 mb-xl-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark">Client</span>
                                                    <p class="m-0">
                                                        {{ $booking->Client->email }}
                                                    </p>
                                                </div>

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
                                                <div class="col-12 col-sm-4 mb-xl-0 d-flex flex-column align-items-start">
                                                    <span class="fs-6 fw-light text-dark">Client</span>
                                                    <p class="m-0">
                                                        {{ $booking->Client->email }}
                                                    </p>
                                                </div>

                                                <div class="col-12 col-sm-3  mb-xl-0 p-0 d-flex flex-column align-items-start">
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


                                            <div class="col-12 col-sm-2 mb-xl-0 d-flex flex-column align-items-end">

                                                <a href="{{ route('bookings-management.view', ['booking' => $booking->id]) }}"
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
                    <h3>Ops....</h3>
                    @if (request()->input('email') == null && request()->input('address_city') == null)
                        <p class="m-0">No bookings is found.</p>
                    @else
                        <p class="m-0">No bookings is matching for your query.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
