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
                    <a href="{{ route('balance-and-recharge.recharge') }}" class="text-decoration-none text-dark">Confirm
                        Recharge Account</a>
                </li>
            </ol>
        </nav>
    </div>
@endsection



@section('main-body')
    <div class="container">
        <div id="update-profile" class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Confirm Account Recharge</h2>
            </div>
            <form action="{{ config('payhere.url') }}" method="post">

                @if (session()->has('profile-update-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-update-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <h3 class="mb-4">Billing Informations</h3>

                    <div class="mb-3 d-flex flex-column">
                        <span>Reference ID</span>
                        <span class="mt-1 fw-bold">{{ $transaction->reference_id }}</span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="name@example.com" readonly value="{{ $data->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Saman"
                            value="{{ $data->first_name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Perera"
                            value="{{ $data->last_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="0xx xxx xxxx"
                            value="{{ $data->contact_number }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Residence</label>
                        <textarea id="address" class="form-control" rows="4">{{ join(",\n", array_filter([$data->address_line_1, $data->address_line_2, $data->address_line_3, $data->address_city->name])) }}.</textarea>

                    </div>


                    <div class="mb-3">
                        <label for="amount" class="form-label">Recharge Amount (Rs)</label>
                        <input type="text" class="form-control  @if ($errors->has('amount')) is-invalid @endif"
                            id="amount" name="amount" placeholder="0xx xxx xxxx"
                            value="{{ number_format($data->amount, 2, '.', '') }}" readonly>
                    </div>

                    <input type="hidden" name="merchant_id" value="{{ config('payhere.merchant_id') }}">

                    <input type="hidden" name="return_url" value="{{ route('balance-and-recharge.recharge.status') }}">
                    <input type="hidden" name="cancel_url" value="{{ route('balance-and-recharge.recharge.status') }}">

                    @if (config('payhere.env') == 'test')
                        <input type="hidden" name="notify_url"
                            value="{{config('payhere.test_notifiaction_tunnel')}}{{ route('payment-listener', [], false) }}">
                    @else
                        <input type="hidden" name="notify_url" value="{{ route('payment-listener') }}">
                    @endif

                    <input type="hidden" name="order_id" value="{{ $transaction->reference_id }}">
                    <input type="hidden" name="items" value="Account Recharge">
                    <input type="hidden" name="currency" value="{{ config('payhere.currency') }}">

                    <input type="hidden" name="address"
                        value="{{ join(",\n", array_filter([$data->address_line_1, $data->address_line_2, $data->address_line_3])) }}">
                    <input type="hidden" name="city" value="{{ $data->address_city->name }}">
                    <input type="hidden" name="country" value="Sri Lanka">

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('balance-and-recharge.recharge') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-left-circle me-2"></i></i>Back</a>


                    <button class="btn btn-primary"><i class="bi bi-credit-card-2-back-fill me-1"></i>Pay Now</button>
                </div>
            </form>
        </div>
    </div>
@endsection
