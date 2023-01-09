@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-body')
    <div class="container">
        <div class="p-0 mt-4" >
            <div class="d-flex justify-content-center align-items-center flex-column py-5">
                <img src="{{ asset('images/ilustrations/feeling-blue.svg') }}" class="mb-4" alt="Empty Image"
                    style="width:220px">
                <h3>Ops....</h3>
                <p class="m-0">You have not been associated with a branch. please get in touch with the system
                    administrator.</p>
            </div>
        </div>
    </div>
@endsection
