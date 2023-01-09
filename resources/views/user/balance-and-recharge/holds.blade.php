@extends('layouts.side-bar')
@include('common.side-bar.side-bar')
@include('user.balance-and-recharge.common')

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
                    <a href="{{ route('balance-and-recharge.holds') }}" class="text-decoration-none text-dark">Holds</a>
                </li>
            </ol>
        </nav>
    </div>
@endsection



@section('main-body')
    <div class="container">
        @yield('summery')

        <div class="row mt-4">
            <div class="col-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('balance-and-recharge.transactions') }}"><i
                                class="bi bi-arrow-left-right me-2"></i>Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active bg-main text-light" aria-current="page"
                            href="{{ route('balance-and-recharge.holds') }}">
                            <i class="bi bi-pause-circle me-2"></i>Holds</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                @if ($transactions->count() > 0)
                    <div class="container">
                        @foreach ($transactions as $transaction)
                            <div class="row bg-white rounded border mt-2 py-2 align-items-center">
                                <div class="col-3 d-flex flex-column">
                                    <span class="fw-lighter">Reference</span>
                                    <span>{{ $transaction->reference_id }}</span>
                                </div>
                                <div class="col-3 d-flex flex-column">
                                    <span class="fw-lighter">Date</span>
                                    <span>{{ $transaction->created_at->format('Y-m-d H:i A') }}</span>
                                </div>
                                <div class="col-3 d-flex flex-column">
                                    <span class="fw-lighter">Amount</span>
                                    <div>
                                        @if ($transaction->amount >= 0)
                                            <span class="text-success me-2"><i class="bi bi-arrow-right"></i></span>
                                        @elseif($transaction->amount < 0)
                                            <span class="text-danger me-2"><i class="bi bi-arrow-left"></i></span>
                                        @endif
                                        <span>Rs {{ number_format(abs($transaction->amount), 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-3 d-flex flex-column">
                                    <span class="fw-lighter">Balance</span>
                                    <span>Rs {{ number_format($transaction->final_balance, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-end mt-2">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                @else
                    <div class="card d-flex justify-content-center align-items-center flex-column py-5">
                        <img src="{{ asset('images/ilustrations/transfer_money.svg') }}" class="mb-5" alt="Empty Image"
                            style="width:220px">
                        <h3>Mmmm....</h3>
                        <p class="m-0">You don't have any pending transactions to show.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
