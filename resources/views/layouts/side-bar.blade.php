@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="{{ mix('components/sidebar/sidebar.css') }}" />
@endpush
@push('foot')
    <script src="{{ mix('components/sidebar/sidebar.js') }}"></script>
@endpush

@section('body')
    <main class="d-flex flex-row ic-scrollbar @if (Cookie::get('sidebar-state') == 'true') expanded @endif">
        <div class="side-bar d-flex flex-column flex-shrink-0 p-3 bg-white shadow">

            @hasSection('sidebar-header')
                @yield('sidebar-header')
            @else
                <div class="sidebar-header bg-light rounded d-flex justify-content-between align-items-center">
                    <img src="{{ asset('images/logo.png') }}"class="logo" alt="Logo" width="100">

                    <button class="sidebar-trigger-btn btn btn-light border rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </button>
                </div>
                <div class="logo-icon mt-4 d-flex justify-content-center">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo icon" width="35px">
                </div>
                <a href="{{ route('account-management') }}" class="text-decoration-none text-dark auth-profile d-flex flex-column align-items-start rounded">
                    <div class="mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor"
                            class="bi bi-person-circle text-main" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                    </div>
                    @if (!Auth::user()->isUserAccount())
                        <span class="bg-info text-white px-2 mt-2 mb-1 rounded">{{ Auth::user()->roleText }}</span>
                    @endif
                    <p class="m-0 text-truncate mw-100"><b>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</b>
                    </p>
                    <p class="m-0 text-truncate mw-100">{{ Auth::user()->email }}</p>
                </a>
            @endif
            @hasSection('sidebar-body')
                <div class="mt-2 sidebar-body">
                    @yield('sidebar-body')
                </div>
            @endif
            @hasSection('sidebar-footer')
                <div>
                    @yield('sidebar-footer')
                </div>
            @endif



        </div>

        <div class="content d-flex flex-column flex-shrink-0 p-3 flex-grow-1 ic-scrollbar">
            <div class="main-header">
                @yield('main-header')
            </div>
            <div class="main-body flex-grow-1">
                @yield('main-body')
            </div>
            <div class="main-footer">
                <span>{{ config('app.copyright') }}</span>
            </div>
        </div>
    </main>



    <div id="logout-model" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Logging Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure that you want to logout from your account?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('auth.logout') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i
                                class="bi bi-box-arrow-right me-1"></i>Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
