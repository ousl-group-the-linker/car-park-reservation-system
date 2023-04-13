@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-receipt me-2 text-main"></i>&nbsp;My Bookings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('my-bookings') }}" class="text-decoration-none text-dark">My Bookings</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Booking #{{ $booking->reference_id }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-body')

    <div class="container">
        <div class="p-0">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Booking #{{ $booking->reference_id }}</h2>
                </div>
                @if (session()->has('message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="container">
                        <div class="row border-bottom pb-4 mb-4">
                            <div class="col-6 d-flex flex-column align-items-start">
                                <span>Status</span>
                                @if ($booking->isPending())
                                    <p class="badge bg-info">Pending</p>
                                @elseif($booking->isCancelled())
                                    <p class="badge bg-danger">Canceled</p>
                                @elseif($booking->isOnGoing())
                                    <p class="badge bg-info">On Going</p>
                                @elseif($booking->isFinished())
                                    <p class="badge bg-success">Finished</p>
                                @endif
                            </div>
                        </div>
                        <div class="row border-bottom pb-4 mb-4">
                            <div class="col-6 d-flex flex-column">
                                <span>Branch</span>
                                <p>{{ $booking->Branch->name }}</p>
                            </div>
                            <div class="col-6 d-flex flex-column">
                                <span>Email</span>
                                <a href="mailto:{{ $booking->Branch->email }}"
                                    class="text-dark text-decoration-none">{{ $booking->Branch->email }}</a>
                            </div>
                            <div class="col-6 d-flex flex-column">
                                <span>Contacts</span>
                                <a href="tel:{{ $booking->Client->contact_number }}"
                                    class="text-dark text-decoration-none">{{ $booking->Branch->contact_number }}</a>
                            </div>
                            <div class="col-6">
                                <span>Address</span>
                                <p>{{ $booking->Branch->AddressText }}</p>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-6">
                                <span class="d-flex align-middle">
                                    <i class="bi bi-car-front-fill me-2"></i>Vehicle No</span>
                                <p class="ps-4">{{ $data->vehicle_no }}</p>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-12">
                                <span class="fw-bold d-flex mb-3">Estimate</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <span class="d-flex align-middle">
                                    <i class="bi bi-hourglass-top me-2"></i>Arrival Date</span>
                                <p class="ps-4">{{ $data->estimate->start_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-middle">
                                    <i class="bi bi-alarm me-2"></i></i>Arrival Time</span>
                                <p class="ps-4">{{ $data->estimate->start_at->format('H:i A') }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <span class="d-flex align-middle">
                                    <i class="bi bi-hourglass-bottom me-2"></i>Release Date</span>
                                <p class="ps-4">{{ $booking->estimated_end_time->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-middle">
                                    <i class="bi bi-alarm me-2"></i></i>Release Time</span>
                                <p class="ps-4">{{ $booking->estimated_end_time->format('H:i A') }}</p>
                            </div>
                        </div>
                        <div class="container bg-light mt-2 border rounded py-3 px-3 text-dark">
                            <div class="row align-items-center">
                                <div class="col-6 d-flex flex-column ps-md-5">
                                    <span class="fw-bold">Estimated Fee</span>
                                    <span class="fs-6">{{ $booking->estimatedHours() }} Hour</span>
                                </div>
                                <div class="col-6">
                                    <span class="fs-1">Rs {{ number_format($booking->estimatedFee(), 2) }} /-</span>
                                </div>
                            </div>
                        </div>


                        @if (isset($data->actual))
                            <div class="row border-top mt-5 pt-5">
                                <div class="col-12">
                                    <span class="fw-bold d-flex mb-3">Actual</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <span class="d-flex align-middle">
                                        <i class="bi bi-hourglass-top me-2"></i>Arrival Date</span>
                                    <p class="ps-4">{{ $data->actual->start_at->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-6">
                                    <span class="d-flex align-middle">
                                        <i class="bi bi-alarm me-2"></i></i>Arrival Time</span>
                                    <p class="ps-4">{{ $data->actual->start_at->format('H:i A') }}</p>
                                </div>
                            </div>
                            @if ($booking->isFinished())
                                <div class="row">
                                    <div class="col-6">
                                        <span class="d-flex align-middle">
                                            <i class="bi bi-hourglass-bottom me-2"></i>Release Date</span>
                                        <p class="ps-4">{{ $data->actual->end_at->format('Y-m-d') }}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="d-flex align-middle">
                                            <i class="bi bi-alarm me-2"></i></i>Release Time</span>
                                        <p class="ps-4">{{ $data->actual->end_at->format('H:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if (!$booking->isFinished())
                                <div class="row">
                                    <div class="col-6">
                                        <span class="d-flex align-middle">
                                            <i class="bi bi-hourglass-bottom me-2"></i>Release Date</span>
                                        <p class="ps-4">N/A</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="d-flex align-middle">
                                            <i class="bi bi-alarm me-2"></i></i>Release Time</span>
                                        <p class="ps-4">N/A</p>
                                    </div>
                                </div>
                            @endif

                            <div class="container bg-light mt-4 border rounded py-3 px-3 mb-4 text-dark">
                                <div class="row align-items-center">
                                    <div class="col-6 d-flex flex-column ps-md-5">
                                        <span class="fw-bold">Total Fee</span>
                                        <span class="fs-6">{{ $data->actual->hours }} Hour</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="fs-1">Rs {{ number_format($data->actual->fee, 2) }}/-</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
                <div class="card-footer d-flex justify-content-end d-flex justify-content-between">
                    <a href="{{ route('my-bookings') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-left-circle me-2"></i></i>Back</a>

                    <div class="d-flex flex-row">

                        @can('markAsCancelled', $booking)
                            <button class="btn btn-light ms-2" data-bs-toggle="modal" data-bs-target="#mark-as-cancelled"><i
                                    class="bi bi-x-circle me-2"></i>Cancel
                                Booking</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <h4>Transactions</h4>
                </div>
            </div>
            @if ($transactions->count() > 0)
                @foreach ($transactions as $transaction)
                    <div class="row bg-white rounded border mt-2 py-2 align-items-center">
                        <div class="col-3 d-flex flex-column">
                            <span class="fw-lighter">Reference</span>
                            <span>{{ $transaction->reference_id }}</span>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <span class="fw-lighter">Date</span>
                            <span>{{ $transaction->created_at->format('Y-m-d H:i A') }}</span>
                        </div>
                        <div class="col-3 d-flex flex-column">
                            <span class="fw-lighter">Amount</span>
                            <div>
                                @if ($transaction->amount >= 0)
                                    <span class="text-success me-2"><i class="bi bi-arrow-right"></i></span>
                                @elseif($transaction->amount < 0)
                                    <span class="text-danger me-2"><i class="bi bi-arrow-left"></i></span>
                                @endif
                                <span>Rs {{ number_format(abs($transaction->amount), 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card d-flex justify-content-center align-items-center flex-column py-5 px-0">
                    <img src="{{ asset('images/ilustrations/transfer_money.svg') }}" class="mb-5" alt="Empty Image"
                        style="width:220px">
                    <h3>Mmmm....</h3>
                    <p class="m-0">You did not made any transactions yets.</p>
                </div>
            @endif
        </div>


        @can('markAsCancelled', $booking)
            <div id="mark-as-cancelled" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancel Booking</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure that you want to cancel this booking?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                            <form action="{{ route('my-bookings.cancel', ['booking' => $booking->reference_id]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    @endsection
