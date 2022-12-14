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
    <div class="d-flex align-items-center justify-content-end flex-grow-1">

    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="row mb-4 p-0 m-0">
            <div class="col-12 d-flex p-0 m-0" style="max-width: 780px">
                <a href="{{ route('super-admin.admin-management.new') }}" class="btn btn-primary">
                    <i class="bi bi-plus-square me-1"></i>New Admin</a>
            </div>
        </div>
        <div class="p-0" style="max-width: 780px">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Search Admins</h2>
                </div>
                <form action="{{ route('super-admin.admin-management') }}" method="get">

                    @if (session()->has('profile-update-success-message'))
                        <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                            {{ session()->get('profile-update-success-message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control  @if ($errors->has('email')) is-invalid @endif"
                                id="email" name="email" placeholder="name@example.com"
                                value="{{ request()->input('email') }}">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('super-admin.admin-management') }}" class="btn btn-light me-2"><i
                                class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i></i>Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="p-0 mt-4" style="max-width: 780px">
            @if ($admin_accounts->count() > 0)
                @foreach ($admin_accounts as $admin_account)
                    <div class="row border rounded bg-light py-3 px-4 mx-0  mb-2">
                        <div class="col-12 col-sm-6 col-xl-2 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">ID</span>
                            <p class="m-0">{{ $admin_account->id }}</p>
                        </div>
                        <div
                            class="text-truncate col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Email</span>
                            <a href="mailto:{{ $admin_account->email }}"
                                class="m-0 text-dark text-dark w-100 text-truncate">{{ $admin_account->email }}</a>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Name</span>
                            <p class="text-truncate m-0">{{ $admin_account->first_name . ' ' . $admin_account->last_name }}
                            </p>
                        </div>

                        <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Branch</span>
                            <p class="text-truncate m-0">N/A</p>
                        </div>
                        <hr class="col-12 d-flex my-2">
                        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 p-0 pb-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Role</span>
                            <p class="badge bg-info m-0">{{ $admin_account->roleText }}</p>
                        </div>
                        <div class="ps-0 ml-0 col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Status</span>
                            @if ($admin_account->is_activated)
                                <p class="badge bg-success m-0">Activated</p>
                            @else
                                <p class="badge bg-danger m-0">Deactivated</p>
                            @endif
                        </div>
                        <div
                            class="ps-0 col-12 col-xl-4 pt-4 pt-xl-0 pe-0 d-flex flex-row justify-content-end align-items-center">

                            @can('view', $admin_account)
                                <a href="{{ route('super-admin.admin-management.view', ['admin' => $admin_account->id]) }}"
                                    class="btn btn-light me-2"><i class="bi bi-box-arrow-up-right me-1"></i>View</a>
                            @endcanany

                            @can('update', $admin_account)
                                <a href="{{ route('super-admin.admin-management.edit', ['admin' => $admin_account->id]) }}"
                                    class="btn btn-light"><i class="bi bi-pencil-fill me-1"></i>Edit</a>
                            @endcanany
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-end mt-2">
                    {{ $admin_accounts->links() }}
                </div>
            @else
                <div class="card d-flex justify-content-center align-items-center flex-column py-5">
                    <img src="{{ asset('images/ilustrations/void.svg') }}" class="mb-4" alt="Empty Image"
                        style="width:220px">
                    <h3>Ops....</h3>
                    @if (request()->input('email') == null)
                        <p class="m-0">No admin account is found.</p>
                    @else
                        <p class="m-0">No admin account is found for your query.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
