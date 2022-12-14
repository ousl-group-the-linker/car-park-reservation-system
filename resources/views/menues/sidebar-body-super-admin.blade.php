@section('sidebar-body-super-admin')
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('super-admin.dashboard') }}" class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('super-admin.dashboard')) active @endif">
                <i class="bi bi-display"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.branches-management') }}" class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('super-admin.branches-management')) active @endif">
                <i class="bi bi-p-circle"></i>
                <span>Parkings</span>
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.bookings-management') }}" class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('super-admin.bookings-management')) active @endif">
                <i class="bi bi-receipt"></i>
                <span>Bookings</span>
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.transactions-management') }}"
                class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('super-admin.transactions-management')) active @endif">
                <i class="bi bi-plus-slash-minus"></i>
                <span>Transactions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.admin-management') }}" class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('super-admin.admin-management')) active @endif">
                <i class="bi bi-people"></i>
                <span>Admins</span>
            </a>
        </li>
        <li>
            <a href="{{ route('account-management') }}" class="nav-link text-dark d-flex align-items-center @if(request()->routeIs('account-management')) active @endif">
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
