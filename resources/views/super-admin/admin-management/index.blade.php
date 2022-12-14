@extends('layouts.side-bar')
@include('menues.sidebar-body-super-admin')

@section('sidebar-body')
   @yield('sidebar-body-super-admin')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0"><i class="bi bi-people me-2 text-main"></i>&nbsp;Admin Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Admins</li>
            </ol>
        </nav>
    </div>
@endsection
