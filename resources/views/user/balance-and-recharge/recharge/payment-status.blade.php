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

    @if ($transaction->PayherePayment->isStatusSuccess())
        <div class="container my-4">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column align-items-center"
                        style="min-height: 420px;max-width: 420px;width:100%">

                        <img src="{{ asset('images/ilustrations/completing.svg') }}" alt="Warning image" width="250">

                        <h1 class="mt-5 text-success">Recharge Successfull</h1>
                        <p>{{ $transaction->PayherePayment->status_message }}</p>

                        <div class="container bg-light rounded mt-2">
                            <div class="row border-bottom py-2">
                                <div class="col-6">Transaction Reference</div>
                                <div class="col-6">{{ $transaction->reference_id }}</div>
                            </div>
                            <div class="row border-bottom py-2">
                                <div class="col-6">Status</div>
                                @if ($transaction->PayherePayment->isStatusSuccess())
                                    <div class="col-6 text-success">Success</div>
                                @elseif($transaction->PayherePayment->isStatusPending())
                                    <div class="col-6 text-info">Pending</div>
                                @elseif($transaction->PayherePayment->isStatusCanceled())
                                    <div class="col-6 text-danger">Canceled</div>
                                @elseif($transaction->PayherePayment->isStatusFailed())
                                    <div class="col-6 text-danger">Failed</div>
                                @elseif($transaction->PayherePayment->isStatusRefunded())
                                    <div class="col-6 text-info">Refunded</div>
                                @else
                                    <div class="col-6 text-danger">Unknown</div>
                                @endif

                            </div>
                            <div class="row py-2">
                                <div class="col-6">Amount</div>
                                <div class="col-6">Rs {{ number_format($transaction->amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col px-0 d-flex">
                                    <a href="{{ route('balance-and-recharge.transactions') }}"
                                        class="btn btn-light w-100"><i
                                            class="bi bi-arrow-left-circle me-2"></i>Continue</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container my-4">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column align-items-center"
                        style="min-height: 420px;max-width: 420px;width:100%">


                        <img src="{{ asset('images/ilustrations/warning.svg') }}" alt="Warning image" width="250">

                        <h1 class="mt-5 text-danger">Ops... Recharge Failed</h1>
                        <p>{{ $transaction->PayherePayment->status_message ?? 'An error occurred while we were processing your payment.' }}
                        </p>

                        <div class="container bg-light rounded mt-5">
                            <div class="row border-bottom py-2">
                                <div class="col-6">Transaction Reference</div>
                                <div class="col-6">{{ $transaction->reference_id }}</div>
                            </div>
                            <div class="row border-bottom py-2">
                                <div class="col-6">Status</div>
                                @if ($transaction->PayherePayment->isStatusSuccess())
                                    <div class="col-6 text-success">Success</div>
                                @elseif($transaction->PayherePayment->isStatusPending())
                                    <div class="col-6 text-info">Pending</div>
                                @elseif($transaction->PayherePayment->isStatusCanceled())
                                    <div class="col-6 text-danger">Canceled</div>
                                @elseif($transaction->PayherePayment->isStatusFailed())
                                    <div class="col-6 text-danger">Failed</div>
                                @elseif($transaction->PayherePayment->isStatusRefunded())
                                    <div class="col-6 text-info">Refunded</div>
                                @else
                                    <div class="col-6 text-danger">Unknown</div>
                                @endif

                            </div>
                            <div class="row py-2">
                                <div class="col-6">Amount</div>
                                <div class="col-6">Rs {{ number_format($transaction->amount, 2) }}</div>
                            </div>
                        </div>
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col px-0 pe-1">
                                    <a href="{{ route('balance-and-recharge.transactions') }}"
                                        class="btn btn-light  w-100"><i
                                            class="bi bi-arrow-left-circle me-2"></i>Continue</a>
                                </div>
                                <div class="col px-0 ps-1">
                                    <a href="{{ route('balance-and-recharge.transactions') }}"
                                        class="btn btn-main text-light  w-100">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Retry</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


@endsection
