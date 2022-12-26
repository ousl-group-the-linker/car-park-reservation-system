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
        <h1 class="fs-4 m-0"><i class="bi bi-p-circle me-2 text-main"></i>&nbsp;Parking Management (Branches)</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Parkings</li>
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
                <a href="{{ route('branches-management.new') }}" class="btn btn-primary">
                    <i class="bi bi-plus-square me-1"></i>New Branch</a>
            </div>
        </div>
        <div class="p-0" style="max-width: 780px">
            <div class="card">
                <div class="card-header">
                    <h2 class="fs-5 m-0">Search Branch</h2>
                </div>
                <form action="{{ route('branches-management') }}" method="get">

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
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <select class="form-select   @if ($errors->has('address_city')) is-invalid @endif"
                                aria-label="City" name="address_city">
                                <option selected value="">Any</option>
                                @foreach ($districts as $district)
                                    <option disabled>{{ $district->name }}</option>

                                    @foreach ($district->Cities as $city)
                                        <option value="{{ $city->id }}"
                                            @if (request()->input('address_city') == $city->id) selected @endif>
                                            &nbsp;&nbsp;&nbsp;{{ $city->name }}</option>
                                    @endforeach

                                    <option disabled></option>
                                @endforeach
                            </select>
                            @if ($errors->has('address_city'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address_city') }}
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('branches-management') }}" class="btn btn-light me-2"><i
                                class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i></i>Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="p-0 mt-4" style="max-width: 780px">
            @if ($branches->count() > 0)
                @foreach ($branches as $branch)
                    <div class="row border rounded bg-light py-3 px-4 mx-0  mb-2">
                        <div class="col-12 col-sm-6 col-xl-2 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">ID</span>
                            <p class="m-0">{{ $branch->id }}</p>
                        </div>
                        <div
                            class="text-truncate col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Email</span>
                            <a href="mailto:{{ $branch->email }}"
                                class="m-0 text-dark text-dark w-100 text-truncate text-decoration-none">{{ $branch->email }}</a>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Name</span>
                            <p class="text-wrap m-0">{{ $branch->name }}
                            </p>
                        </div>

                        <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0 p-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">City</span>
                            <p class="text-wrap m-0 text-wrap">{{ $branch->City->name }}</p>
                        </div>
                        <hr class="col-12 d-flex my-2">
                        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 p-0 pb-0 d-flex flex-column align-items-start">
                            <span class="fs-6 fw-light">Manager</span>

                            @if (isset($branch->Manager))
                                <p class="m-0 text-wrap">
                                    {{ $branch->Manager->first_name . ' ' . $branch->Manager->last_name }}</p>
                            @else
                                <p class="m-0 text-wrap">No Manager</p>
                            @endif
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4 mb-2 mb-xl-0 p-0 pb-0 d-flex flex-column align-items-start">
                        </div>
                        <div
                            class="ps-0 col-12 col-xl-4 pt-4 pt-xl-0 pe-0 d-flex flex-row justify-content-end align-items-center">

                            @can('view', $branch)
                                <a href="{{ route('branches-management.view', ['branch' => $branch->id]) }}"
                                    class="btn btn-light me-2"><i class="bi bi-box-arrow-up-right me-1"></i>View</a>
                            @endcanany

                            @can('update', $branch)
                                <a href="{{ route('branches-management.edit', ['branch' => $branch->id]) }}"
                                    class="btn btn-light"><i class="bi bi-pencil-fill me-1"></i>Edit</a>
                            @endcanany
                        </div>
                    </div>
                @endforeach
                <div class="d-flex justify-content-end mt-2">
                    {{ $branches->links() }}
                </div>
            @else
                <div class="card d-flex justify-content-center align-items-center flex-column py-5">
                    <img src="{{ asset('images/ilustrations/feeling-blue.svg') }}" class="mb-4" alt="Empty Image"
                        style="width:220px">
                    <h3>Ops....</h3>
                    @if (request()->input('email') == null && request()->input('address_city') == null)
                        <p class="m-0">No branch is found.</p>
                    @else
                        <p class="m-0">No branch is matching for your query.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
