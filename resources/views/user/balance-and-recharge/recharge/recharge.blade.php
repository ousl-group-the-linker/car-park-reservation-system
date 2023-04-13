@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0">
            <i class="bi bi-wallet2 me-2 text-main"></i>&nbsp;Balance & Recharge
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none text-dark">Balance & Recharge</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="{{ route('balance-and-recharge.recharge') }}" class="text-decoration-none text-dark">Recharge
                        Account</a>
                </li>
            </ol>
        </nav>
    </div>
@endsection



@section('main-body')
    <div class="container">
        <div id="update-profile" class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Account Recharge</h2>
            </div>
            <form action="{{ route('balance-and-recharge.recharge.confirm') }}" method="post">
                @csrf

                @if (session()->has('profile-update-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-update-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <h3 class="mb-4">Billing Informations</h3>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="name@example.com" value="{{ Auth::user()->email }}">
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @if ($errors->has('first_name')) is-invalid @endif"
                            id="first_name" name="first_name" placeholder="Saman" value="{{ Auth::user()->first_name }}">
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
                            value="{{ old('last_name', Auth::user()->last_name) }}">
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
                            value="{{ old('address_line_1', Auth::user()->address_line_1) }}">
                        @if ($errors->has('address_line_1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_1') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->has('address_line_2')) is-invalid @endif"
                            id="address_line_2" name="address_line_2" placeholder="Address line 2"
                            value="{{ old('address_line_2', Auth::user()->address_line_2) }}">
                        @if ($errors->has('address_line_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_2') }}
                            </div>
                        @endif

                        <input type="text" class="form-control mt-2  @if ($errors->has('address_line_3')) is-invalid @endif"
                            id="address_line_3" name="address_line_3" placeholder="Address line 3"
                            value="{{ old('address_line_3', Auth::user()->address_line_3) }}">
                        @if ($errors->has('address_line_3'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_3') }}
                            </div>
                        @endif

                        <select class="form-select mt-2  @if ($errors->has('address_city')) is-invalid @endif"
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
                            value="{{ old('contact_number', Auth::user()->contact_number) }}">
                        @if ($errors->has('contact_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('contact_number') }}
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Recharge Amount (Rs)</label>
                        <input type="number" class="form-control  @if ($errors->has('amount')) is-invalid @endif"
                            id="amount" name="amount" placeholder="0.00" step="0.1" min="50.00" value="{{ number_format(old('amount', 50.00), 2, '.', '') }}">
                        @if ($errors->has('amount'))
                            <div class="invalid-feedback">
                                {{ $errors->first('amount') }}
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('balance-and-recharge.transactions') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-left-circle me-2"></i></i>Back</a>


                    <div>
                        <a href="{{ route('balance-and-recharge.recharge') }}" class="btn btn-light me-2"><i
                                class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-credit-card-2-back-fill me-1"></i>Pay Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
