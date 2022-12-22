@extends('layouts.side-bar')
@include('menues.sidebar-body-super-admin')
@include('super-admin.admin-management.branch-select')

@section('sidebar-body')
    @yield('sidebar-body-super-admin')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-people me-2 text-main"></i>&nbsp;Admin Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('super-admin.admin-management') }}" class="text-dark text-decoration-none">Admins</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Admin ID#{{ $admin->id }}</li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>


@endsection

@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Edit Admin ID#{{ $admin->id }}</h2>
            </div>
            <form action="{{ route('super-admin.admin-management.edit', ['admin' => $admin->id]) }}" method="post">
                @csrf

                @if (session()->has('profile-update-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-update-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="name@example.com" readonly disabled value="{{ $admin->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @if ($errors->has('first_name')) is-invalid @endif"
                            id="first_name" name="first_name" placeholder="Saman"
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
                            id="last_name" name="last_name" placeholder="Perera"
                            value="{{ old('last_name', $admin->last_name) }}">
                        @if ($errors->has('last_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('last_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Residence</label>
                        <input type="text" class="form-control @if ($errors->has('address_line_1')) is-invalid @endif"
                            id="address_line_1" name="address_line_1" placeholder="Address line 1"
                            value="{{ old('address_line_1', $admin->address_line_1) }}">
                        @if ($errors->has('address_line_1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_1') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->has('address_line_2')) is-invalid @endif"
                            id="address_line_2" name="address_line_2" placeholder="Address line 2"
                            value="{{ old('address_line_2', $admin->address_line_2) }}">
                        @if ($errors->has('address_line_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_2') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->has('address_line_3')) is-invalid @endif"
                            id="address_line_3" name="address_line_3" placeholder="Address line 3"
                            value="{{ old('address_line_3', $admin->address_line_3) }}">
                        @if ($errors->has('address_line_3'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_3') }}
                            </div>
                        @endif

                        <select class="form-select mt-2  @if ($errors->profileUpdate->has('address_city')) is-invalid @endif"
                            aria-label="City" name="address_city">
                            <option selected disabled>Choose one</option>
                            @foreach ($districts as $district)
                                <option disabled>{{ $district->name }}</option>

                                @foreach ($district->Cities as $city)
                                    <option value="{{ $city->id }}" @if (old('address_city', $admin->address_city_id) == $city->id) selected @endif>
                                        &nbsp;&nbsp;&nbsp;{{ $city->name }}</option>
                                @endforeach

                                <option disabled></option>
                            @endforeach
                        </select>
                        @if ($errors->has('address_city'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_city') }}
                            </div>
                        @endif

                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control  @if ($errors->has('contact_number')) is-invalid @endif"
                            id="contact_number" name="contact_number" placeholder="0xx xxx xxxx"
                            value="{{ old('contact_number', $admin->contact_number) }}">
                        @if ($errors->has('contact_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('contact_number') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>

                        <select class="form-select @if ($errors->has('role')) is-invalid @endif" aria-label="City"
                            name="role" required>
                            <option selected disabled>Choose one</option>
                            @foreach ($roles as $id => $role)
                                <option value="{{ $id }}" @if (old('role', $admin->role) == $id) selected @endif>
                                    {{ $role }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('role'))
                            <div class="invalid-feedback">
                                {{ $errors->first('role') }}
                            </div>
                        @endif

                    </div>

                    @yield('branch-select-input')

                    <div class="mb-3">
                        <label for="is_activated" class="form-label">Status</label>

                        <select class="form-select @if ($errors->has('is_activated')) is-invalid @endif"
                            aria-label="City" name="is_activated" required>
                            <option selected disabled>Choose one</option>

                            <option value="1" @if ((int) old('is_activated', $admin->is_activated) === 1) selected @endif>Activated</option>
                            <option value="0" @if ((int) old('is_activated', $admin->is_activated) === 0) selected @endif>Deactivated</option>

                        </select>
                        @if ($errors->has('is_activated'))
                            <div class="invalid-feedback">
                                {{ $errors->first('is_activated') }}
                            </div>
                        @endif

                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('super-admin.admin-management') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-left-circle me-1"></i>Back</a>

                    <div class="d-flex flex-row">
                        <a href="{{ route('super-admin.admin-management.edit', ['admin' => $admin->id]) }}"
                            class="btn btn-light me-2"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @yield('branch-select')z
@endsection



