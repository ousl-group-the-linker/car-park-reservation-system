@extends('layouts.side-bar')
@include('admin.branch-management.edit-update-sections')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <div class="header d-flex flex-column justify-content-center">
            <h1 class="fs-4 m-0"><i class="bi bi-p-circle me-2 text-main"></i>&nbsp;Parking Management (Branches)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active " aria-current="page">
                        <a href="{{ route('branches-management') }}" class="text-dark text-decoration-none">Parkings</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Branch #{{ $branch->id }}</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Branch # {{ $branch->id }}</h2>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" value="{{ $branch->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="address_line_1" class="form-label">Address</label>
                    <input type="text" class="form-control" value="{{ $branch->address_line_1 }}" readonly>

                    @if (strlen($branch->address_line_2) > 0)
                        <input type="text" class="form-control mt-2 " value="{{ $branch->address_line_2 }}" readonly>
                    @endif

                    @if (strlen($branch->address_line_3) > 0)
                        <input type="text" class="form-control mt-2" value="{{ $branch->address_line_3 }}" readonly>
                    @endif

                    <input type="email" class="form-control mt-2" value="{{ $branch->City->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" value="{{ $branch->email }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" value="{{ $branch->contact_number }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="parking_slots" class="form-label">Parking Capacity</label>
                    <input type="text" class="form-control" value="{{ $branch->parking_slots }}" readonly>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                @can('view', $branch)
                    <a class="btn btn-light" href="{{ route('branches-management.view', ['branch' => $branch->id]) }}">
                        <i class="bi bi-arrow-left-circle me-2"></i>Back</a>
                @endcan


                @can('update', $branch)
                    <a class="btn btn-primary" href="{{ route('branches-management.edit', ['branch' => $branch->id]) }}"><i
                            class="bi bi-pencil-square me-2"></i></i>Edit</a>
                @endcan
            </div>
        </div>
    </div>
@endsection
