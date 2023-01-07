@section('sidebar-body-user')
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('find-parking-lot') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('find-parking-lot')) active @endif">
                <i class="bi bi-p-circle"></i>
                <span>Find Parking</span>
            </a>
        </li>
        <li>
            <a href="{{ route('my-bookings') }}"
                class="nav-link text-dark d-flex align-items-center
                    @if (request()->routeIs('my-bookings') ||
                        request()->routeIs('my-bookings') ||
                        request()->routeIs('my-bookings.new') ||
                        request()->routeIs('my-bookings.view')) active @endif">
                <i class="bi bi-receipt"></i>
                <span>My Bookings</span>
            </a>
        </li>
        <li>
            <a href="{{ route('find-parking-lot') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('find-parking-lot')) active @endif">
                <i class="bi bi-display"></i>
                <span>Balance & Recharge</span>
            </a>
        </li>
        <li>
            <a href="{{ route('account-management') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('account-management')) active @endif">
                <i class="bi bi-person-gear"></i>
                <span>Account & Profile</span>
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="nav-link text-dark d-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#logout-model">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
@endsection
