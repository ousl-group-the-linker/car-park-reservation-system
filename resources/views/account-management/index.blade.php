@extends('layouts.side-bar')
@include('menues.sidebar-body-super-admin')

@section('sidebar-body')
    @yield('sidebar-body-super-admin')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-person-gear me-2 text-main"></i>&nbsp;Account & Profile Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Account & Profile</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div id="update-profile" class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">My Profile</h2>
            </div>
            <form action="{{ route('account-management.update') }}" method="post">
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
                            placeholder="name@example.com" readonly disabled value="{{ Auth::user()->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @if ($errors->profileUpdate->has('first_name')) is-invalid @endif"
                            id="first_name" name="first_name" placeholder="Saman" value="{{ Auth::user()->first_name }}">
                        @if ($errors->profileUpdate->has('first_name'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('first_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">First Name</label>
                        <input type="text" class="form-control  @if ($errors->profileUpdate->has('last_name')) is-invalid @endif"
                            id="last_name" name="last_name" placeholder="Perera"
                            value="{{ old('last_name', Auth::user()->last_name) }}">
                        @if ($errors->profileUpdate->has('last_name'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('last_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Residence</label>
                        <input type="text" class="form-control @if ($errors->profileUpdate->has('address_line_1')) is-invalid @endif"
                            id="address_line_1" name="address_line_1" placeholder="Address line 1"
                            value="{{ old('address_line_1', Auth::user()->address_line_1) }}">
                        @if ($errors->profileUpdate->has('address_line_1'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('address_line_1') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->profileUpdate->has('address_line_2')) is-invalid @endif"
                            id="address_line_2" name="address_line_2" placeholder="Address line 2"
                            value="{{ old('address_line_2', Auth::user()->address_line_2) }}">
                        @if ($errors->profileUpdate->has('address_line_2'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('address_line_2') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->profileUpdate->has('address_line_3')) is-invalid @endif"
                            id="address_line_3" name="address_line_3" placeholder="Address line 3"
                            value="{{ old('address_line_3', Auth::user()->address_line_3) }}">
                        @if ($errors->profileUpdate->has('address_line_3'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('address_line_3') }}
                            </div>
                        @endif

                        <select class="form-select mt-2  @if ($errors->profileUpdate->has('address_city')) is-invalid @endif"
                            aria-label="City" name="address_city">
                            <option selected disabled>Choose one</option>
                            @foreach ($districts as $district)
                                <option disabled>{{ $district->name }}</option>

                                @foreach ($district->Cities as $city)
                                    <option value="{{ $city->id }}" @if (old('address_city', Auth::user()->address_city_id) == $city->id) selected @endif>
                                        &nbsp;&nbsp;&nbsp;{{ $city->name }}</option>
                                @endforeach

                                <option disabled></option>
                            @endforeach
                        </select>
                        @if ($errors->profileUpdate->has('address_city'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('address_city') }}
                            </div>
                        @endif


                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control  @if ($errors->profileUpdate->has('contact_number')) is-invalid @endif"
                            id="contact_number" name="contact_number" placeholder="0xx xxx xxxx"
                            value="{{ old('contact_number', Auth::user()->contact_number) }}">
                        @if ($errors->profileUpdate->has('contact_number'))
                            <div class="invalid-feedback">
                                {{ $errors->profileUpdate->first('contact_number') }}
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('account-management') }}" class="btn btn-light me-2"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
        <div id="change-password" class="card mt-5" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Change Password</h2>
            </div>
            <form action="{{ route('account-management.change-password') }}" method="post">
                @csrf

                @if (session()->has('change-password-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('change-password-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @if ($errors->changePasswordBag->has('current_password')) is-invalid @endif"
                            id="current_password" name="current_password"
                            placeholder="Type your current password here..." required>
                        @if ($errors->changePasswordBag->has('current_password'))
                            <div class="invalid-feedback">
                                {{ $errors->changePasswordBag->first('current_password') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control @if ($errors->changePasswordBag->has('new_password')) is-invalid @endif"
                            id="new_password" name="new_password" placeholder="Type your new password here..." required>

                        @if ($errors->changePasswordBag->has('new_password'))
                            <div class="invalid-feedback">
                                {{ $errors->changePasswordBag->first('new_password') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control @if ($errors->changePasswordBag->has('new_password_confirmation')) is-invalid @endif"
                            id="new_password_confirmation" name="new_password_confirmation"
                            placeholder="Retype your new password here..." required>
                        @if ($errors->changePasswordBag->has('new_password_confirmation'))
                            <div class="invalid-feedback">
                                {{ $errors->changePasswordBag->first('new_password_confirmation') }}
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('account-management') }}" class="btn btn-light me-2"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
