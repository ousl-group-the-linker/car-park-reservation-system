@extends('layouts.app')


@section('body')
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100  p-2 p-md-4">
        <div class="card w-100 shadow border-0" style="max-width: 400px;">
            <div class="py-4 d-flex align-items-center justify-content-center top-to-bottom-gradient m-2 rounded">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 120px">
            </div>

            <div class="mx-4">
                <h1 class="text-main mb-1 fs-2">Forgot Password</h1>
                <p class="fw-lighter">Enter your account’s email address to reset your password.</p>
            </div>

            @if (session()->has('success-message'))
                <div class="alert alert-success mx-4 mt-2" role="alert">
                    {{ session()->get('success-message') }}
                </div>
            @endif

            @error("error-message")
                <div class="alert alert-danger mx-4 mt-2" role="alert">
                    {{ $message }}
                </div>
            @enderror



            <form action="{{ route('auth.forgot-password.step.1') }}" method="post">
                @csrf
                <div class="m-4 mt-2">
                    <div class="mb-2">
                        <label for="email" class="form-label mb-1">Email address</label>
                        <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email"
                            name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-main text-light w-100">Next</button>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <a rel="stylesheet" href="{{ route('auth.login') }}" class="text-dark">Goto Login</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <span class="fs-7">{{ config('app.copyright') }}</span>
        </div>
    </div>
@endsection
