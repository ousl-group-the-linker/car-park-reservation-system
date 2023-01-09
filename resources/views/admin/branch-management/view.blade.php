@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-p-circle me-2 text-main"></i>&nbsp;Parking Management (Branches)</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Parkings</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center justify-content-end flex-grow-1">

    </div>
@endsection

@section('main-body')
    <div class="container">

        <div class="p-0" style="max-width: 780px">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Parking #{{ $branch->id }} - {{ $branch->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-4 mb-2">
                                <span class="fw-lighter">Name</span>
                                <p class="m-0">{{ $branch->name }}</p>
                            </div>
                            <div class="col-4 mb-2">
                                <span class="fw-lighter">City</span>
                                <p class="m-0">{{ $branch->City->name }}</p>
                            </div>

                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <span class="fw-lighter">Reservations</span>
                                <p class="m-0">{{ $branch->reservedSlots }}</p>
                            </div>
                            <div class="col-4">
                                <span class="fw-lighter">Capacity</span>
                                <p class="m-0">{{ $branch->parking_slots }}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a class="btn btn-light" href="{{ route('bookings-management') }}"><i
                                class="bi bi-receipt me-2"></i>Booking Management</a>


                        @if (Auth::user()->isSuperAdminAccount() || Auth::user()->isManagerAccount())
                            <a class="btn btn-light"
                                href="{{ route('branches-management.admin-management', ['branch' => $branch->id]) }}"
                                target="_blank"><i class="bi bi-people me-2"></i>Admin Management</a>
                        @endif
                    </div>
                    <div>
                        @can('view', $branch)
                            <a class="btn btn-light"
                                href="{{ route('branches-management.view-extra', ['branch' => $branch->id]) }}">
                                <i class="bi bi-box-arrow-up-right me-2"></i>View</a>
                        @endcan
                        @can('update', $branch)
                            <a class="btn btn-light"
                                href="{{ route('branches-management.edit', ['branch' => $branch->id]) }}"><i
                                    class="bi bi-pencil-square me-2"></i></i>Edit</a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Capacity</h2>
                </div>
                <div class="card-body">
                    <span class="d-block mb-2">{{ $branch->reservedSlots()->count() }} of {{ $branch->parking_slots }}
                        Booked
                        ({{ ($branch->reservedSlots()->count() / $branch->parking_slots) * 100 }})</span>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar"
                            aria-valuenow="{{ $branch->reservedSlots()->count() }}" aria-valuemin="0"
                            aria-valuemax="{{ $branch->parking_slots }}"
                            style="width: {{ $branch->reservedSlots()->count() }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
