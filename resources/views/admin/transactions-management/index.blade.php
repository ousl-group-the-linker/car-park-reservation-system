@extends('layouts.side-bar')

@section('sidebar-body')
    @if (Auth::user()->isSuperAdminAccount())
        @include('menues.sidebar-body-super-admin')
        @yield('sidebar-body-super-admin')
    @elseif(Auth::user()->isManagerAccount())
        @include('menues.sidebar-body-admin')
        @yield('sidebar-body-admin')
    @elseif(Auth::user()->isCounterAccount())
        @include('menues.sidebar-body-user')
        @yield('sidebar-body-user')
    @endif
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-plus-slash-minus me-2 text-main"></i>&nbsp;Transactions</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Transactions</li>
            </ol>
        </nav>
    </div>
@endsection
