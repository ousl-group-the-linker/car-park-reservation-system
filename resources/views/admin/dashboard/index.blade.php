@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-display me-2 text-main"></i>&nbsp;Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-display me-2 text-main"></i>&nbsp;Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection


@section('main-body')
    @if (Auth::user()->isManagerAccount() && Auth::user()->ManageBranch == null)
        <div class="container w-100 py-4 d-flex justify-content-center align-items-center flex-column"
            style=";min-height:420px">
            <img src="{{ asset('images/ilustrations/lost-online.svg') }}" alt="Lost online" width="120">
            <h3 class="mt-4">Ops...</h3>
            <p class="text-center">You are not associated with any branches at the movement, please contact your system
                administrator.</p>
        </div>
    @endif
    @if (Auth::user()->isCounterAccount() && Auth::user()->WorkForBranch == null)
        <div class="container w-100 py-4 d-flex justify-content-center align-items-center flex-column"
            style=";min-height:420px">
            <img src="{{ asset('images/ilustrations/lost-online.svg') }}" alt="Lost online" width="120">
            <h3 class="mt-4">Ops...</h3>
            <p class="text-center">You are not associated with any branches at the movement, please contact your system
                administrator.</p>
        </div>
    @endif
@endsection
