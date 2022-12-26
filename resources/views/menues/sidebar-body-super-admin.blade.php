@section('sidebar-body-super-admin')
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('super-admin.dashboard') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('super-admin.dashboard')) active @endif">
                <i class="bi bi-display"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('branches-management') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('branches-management') ||
                    request()->routeIs('branches-management.edit') ||
                    request()->routeIs('branches-management.view') ||
                    request()->routeIs('branches-management.new') ||
                    request()->routeIs('branches-management.admin-management') ||
                    request()->routeIs('branches-management.admin-management.new')) active @endif">
                <i class="bi bi-p-circle"></i>
                <span>Branch Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('bookings-management') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('bookings-management')) active @endif">
                <i class="bi bi-receipt"></i>
                <span>Bookings Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('transactions-management') }}"
                class="nav-link text-dark d-flex align-items-center @if (request()->routeIs('transactions-management')) active @endif">
                <i class="bi bi-plus-slash-minus"></i>
                <span>Transactions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('super-admin.admin-management') }}"
                class="nav-link text-dark d-flex align-items-center
                @if (request()->routeIs('super-admin.admin-management') ||
                    request()->routeIs('super-admin.admin-management.view') ||
                    request()->routeIs('super-admin.admin-management.edit') ||
                    request()->routeIs('super-admin.admin-management.new')) active @endif">
                <i class="bi bi-people"></i>
                <span>Admin Management</span>
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
