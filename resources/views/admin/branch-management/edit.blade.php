@extends('layouts.side-bar')
@include('admin.branch-management.edit-update-sections')
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
        <div class="header d-flex flex-column justify-content-center">
            <h1 class="fs-4 m-0"><i class="bi bi-p-circle me-2 text-main"></i>&nbsp;Parking Management (Branches)</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active " aria-current="page">
                        <a href="{{ route('branches-management') }}" class="text-dark text-decoration-none">Parkings</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Branch #{{ $branch->id }}</li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">Edit Branch # {{ $branch->id }}</h2>
            </div>
            <form action="{{ route('branches-management.edit', ['branch' => $branch->id]) }}" method="post">
                @csrf

                @yield('form-body')
            </form>
        </div>
    </div>

    @yield('choose-manager-model')
@endsection
