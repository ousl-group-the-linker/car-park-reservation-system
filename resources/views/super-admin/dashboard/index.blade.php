@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-display me-2 text-main"></i>&nbsp;Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="row mb-2">
            <div class="col-12">
                <span class="fs-2">Users & Branches</span>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Users</span>
                    <p class="fs-1 mb-0">{{$users_count}}</p>
                </div>
            </div>
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Branches</span>
                    <p class="fs-1 mb-0">{{$branches_count}}</p>
                </div>
            </div>
        </div>
        <div class="row mb-2 ">
            <div class="col-12">
                <span class="fs-2">Current Capacity</span>
            </div>
        </div>
        <div class="row  mb-4">
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Total Capacity</span>
                    <p class="fs-1 mb-0">{{$total_parking_slots}}</p>
                </div>
            </div>
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Occupied</span>
                    <p class="fs-1 mb-0">100</p>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <span class="fs-2">Today’s</span>
            </div>
        </div>
        <div class="row  mb-4">
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Pending Bookings</span>
                    <p class="fs-1 mb-0">{{$pending_bookings_count}}</p>
                </div>
            </div>
            <div class="col-12 col-md-4 pb-2 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Ongoing Bookings</span>
                    <p class="fs-1 mb-0">{{$ongoing_bookings_count}}</p>
                </div>
            </div>
            <div class="col-12 col-md-4 pb-md-0 col-xl-3">
                <div class="card  py-4 px-4">
                    <span class="fs-4 fw-lighter">Finished Bookings</span>
                    <p class="fs-1 mb-0">{{$finished_bookings_count}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
