@extends('layouts.side-bar')
@include('menues.sidebar-body-super-admin')

@section('sidebar-body')
    @yield('sidebar-body-super-admin')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-people me-2 text-main"></i>&nbsp;Admin Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('super-admin.admin-management') }}" class="text-dark">Admins</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Admin ID#{{ $admin->id }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Admin ID#{{ $admin->id }}</h2>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com"
                        readonly value="{{ $admin->email }}">
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control @if ($errors->has('first_name')) is-invalid @endif"
                        id="first_name" name="first_name" readonly placeholder="Saman"
                        value="{{ old('first_name', $admin->first_name) }}">
                    @if ($errors->has('first_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('first_name') }}
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">First Name</label>
                    <input type="text" class="form-control  @if ($errors->has('last_name')) is-invalid @endif"
                        id="last_name" name="last_name" readonly placeholder="Perera"
                        value="{{ old('last_name', $admin->last_name) }}">
                    @if ($errors->has('last_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('last_name') }}
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="address_line_1" class="form-label">Residence</label>
                    <input type="text" class="form-control" id="address_line_1" name="address_line_1" readonly
                        placeholder="Address line 1" value="{{ $admin->address_line_1 }}">

                    <input type="text" class="form-control mt-2  @if ($errors->has('address_line_2')) is-invalid @endif"
                        id="address_line_2" name="address_line_2" readonly placeholder="Address line 2"
                        value="{{ $admin->address_line_2 }}">
                    <input type="text" class="form-control mt-2" id="address_line_3" name="address_line_3" readonly
                        placeholder="Address line 3" value="{{ $admin->address_line_3 }}">
                    <input type="text" class="form-control mt-2" id="address_line_3" name="address_line_3" readonly
                        placeholder="Address line 3" value="{{ $admin->City->name }}">
                </div>
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                        placeholder="0xx xxx xxxx" value="{{ $admin->contact_number }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Role</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                        placeholder="0xx xxx xxxx" value="{{ $admin->roleText }}" readonly>
                </div>
                <div class="mb-3 d-flex flex-column align-items-start">
                    <label class="form-label">Status</label>
                    @if ($admin->is_activated)
                        <span class="badge bg-success">Activated</span>
                    @else
                        <span class="badge bg-danger">Deactivated</span>
                    @endif
                </div>


            </div>
            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('super-admin.admin-management') }}" class="btn btn-light me-2"><i
                        class="bi bi-arrow-left-circle me-1"></i>Back</a>

                @can('update', $admin)
                    <a href="{{ route('super-admin.admin-management.edit', ['admin' => $admin->id]) }}"
                        class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                @endcan
            </div>
        </div>
    </div>
@endsection
