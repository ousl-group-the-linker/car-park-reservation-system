@section('common-side-bar')
@if (Auth::user()->isSuperAdminAccount())
    @include('menues.sidebar-body-super-admin')
    @yield('sidebar-body-super-admin')
@elseif(Auth::user()->isManagerAccount() || Auth::user()->isCounterAccount())
    @include('menues.sidebar-body-admin')
    @yield('sidebar-body-admin')
@elseif(Auth::user()->isUserAccount())
    @include('menues.sidebar-body-user')
    @yield('sidebar-body-user')
@endif
@endsection
