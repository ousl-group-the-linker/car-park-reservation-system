@extends('layouts.app')


@section('body')
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 p-2 p-md-4">
        <div class="card w-100 shadow border-0" style="max-width: 620px;">
            <div class="py-4 d-flex align-items-center justify-content-center top-to-bottom-gradient m-2 rounded">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 120px">
            </div>
            <div class="mx-4">
                <h1 class="text-main mb-1 fs-2">Sign Up</h1>
                <p class="fw-lighter">Welcome to our platform, please fill all fields to
                    create an account.</p>
            </div>
            <form action="{{ route('auth.register') }}" method="post">
                @csrf
                <div class="m-4 mt-2">
                    <div class="mb-2">
                        <label for="email" class="form-label mb-1">Email address</label>
                        <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email"
                            value="{{ old('email') }}" name="email" placeholder="name@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="first_name" class="form-label mb-1">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                            value="{{ old('first_name') }}" name="first_name" placeholder="Enter your first name" required>
                        @error('first_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="last_name" class="form-label mb-1">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                            value="{{ old('last_name') }}" name="last_name" placeholder="Enter your last name" required>
                        @error('last_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Residence</label>
                        <input type="text" class="form-control @error('address_line_1') is-invalid @enderror"
                            id="address_line_1" name="address_line_1" placeholder="Address line 1"
                            value="{{ old('address_line_1') }}">
                        @error('address_line_1')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <input type="text" class="form-control mt-2 @error('address_line_2') is-invalid @enderror"
                            id="address_line_2" name="address_line_2" placeholder="Address line 2"
                            value="{{ old('address_line_2') }}">
                        @error('address_line_2')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <input type="text" class="form-control mt-2 @error('address_line_3') is-invalid @enderror"
                            id="address_line_3" name="address_line_3" placeholder="Address line 3"
                            value="{{ old('address_line_3') }}">
                        @error('address_line_3')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <select class="form-select mt-2 @error('selected') is-invalid @enderror" aria-label="City"
                            name="address_city">
                            <option selected disabled>Choose one</option>
                            @foreach ($districts as $district)
                                <option disabled>{{ $district->name }}</option>

                                @foreach ($district->Cities as $city)
                                    <option value="{{ $city->id }}" @if ($city->id == old('address_city')) selected @endif>
                                        &nbsp;&nbsp;&nbsp;{{ $city->name }}</option>
                                @endforeach

                                <option disabled></option>
                            @endforeach
                        </select>
                        @error('address_city')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror


                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                            id="contact_number" name="contact_number" placeholder="0xx xxx xxxx"
                            value="{{ old('contact_number') }}">
                        @error('contact_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label mb-1">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Type your password" required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="password_confirmation" class="form-label mb-1">Confirm Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" id="password_confirmation" placeholder="Retype your password"
                            required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input @error('i_aggree') is-invalid @enderror" type="checkbox"
                            name="i_aggree" id="i_aggree" @if (old('i_aggree')) checked @endif>
                        <label class="form-check-label" for="i_aggree">
                            I agree to the terms and conditions
                        </label>
                        @error('i_aggree')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-main text-light w-100">Create An Account</button>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <a rel="stylesheet" href="{{ route('auth.login') }}" class="text-dark">Already have an
                            account?</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <span class="fs-7">{{ config('app.copyright') }}</span>
        </div>
    </div>
@endsection
