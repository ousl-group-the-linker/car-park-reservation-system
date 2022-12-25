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
        <h1 class="fs-4 m-0"><i class="bi bi-p-circle me-2 text-main"></i>&nbsp;Branch #{{ $branch->id }} |
            {{ $branch->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('branches-management') }}" class="text-decoration-none text-dark">Parkings</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('branches-management.view', ['branch' => $branch->id]) }}"
                        class="text-decoration-none text-dark">Branch
                        #{{ $branch->id }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Admin Management</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex align-items-center justify-content-end flex-grow-1">

    </div>
@endsection

@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 680px">
            <div class="card-header">
                <h2 class="fs-5 m-0">New Admin</h2>
            </div>
            <form action="{{ route('branches-management.admin-management.new', ['branch' => $branch->id]) }}"
                method="post">
                @csrf

                @if (session()->has('profile-create-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-create-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                            id="email" name="email" placeholder="name@example.com" value="{{ old('email') }}"
                            required>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control @if ($errors->has('first_name')) is-invalid @endif"
                            id="first_name" name="first_name" placeholder="Saman" value="{{ old('first_name') }}" required>
                        @if ($errors->has('first_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('first_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">First Name</label>
                        <input type="text" class="form-control  @if ($errors->has('last_name')) is-invalid @endif"
                            id="last_name" name="last_name" placeholder="Perera" value="{{ old('last_name') }}" required>
                        @if ($errors->has('last_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('last_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Residence</label>
                        <input type="text" class="form-control @if ($errors->has('address_line_1')) is-invalid @endif"
                            id="address_line_1" name="address_line_1" placeholder="Address line 1"
                            value="{{ old('address_line_1') }}" required>
                        @if ($errors->has('address_line_1'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_1') }}
                            </div>
                        @endif

                        <input type="text"
                            class="form-control mt-2  @if ($errors->has('address_line_2')) is-invalid @endif"
                            id="address_line_2" name="address_line_2" placeholder="Address line 2"
                            value="{{ old('address_line_2') }}" required>
                        @if ($errors->has('address_line_2'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_2') }}
                            </div>
                        @endif

                        <input type="text"
                            class="form-control mt-2  @if ($errors->has('address_line_3')) is-invalid @endif"
                            id="address_line_3" name="address_line_3" placeholder="Address line 3"
                            value="{{ old('address_line_3') }}">
                        @if ($errors->has('address_line_3'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_line_3') }}
                            </div>
                        @endif

                        <select class="form-select mt-2  @if ($errors->has('address_city')) is-invalid @endif"
                            aria-label="City" name="address_city" required>
                            <option selected disabled>Choose one</option>
                            @foreach ($districts as $district)
                                <option disabled>{{ $district->name }}</option>

                                @foreach ($district->Cities as $city)
                                    <option value="{{ $city->id }}" @if (old('address_city') == $city->id) selected @endif>
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
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control  @if ($errors->has('contact_number')) is-invalid @endif"
                            id="contact_number" name="contact_number" placeholder="0xx xxx xxxx"
                            value="{{ old('contact_number') }}" required>
                        @if ($errors->has('contact_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('contact_number') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Temporary Password</label>



                        <div class="input-group">
                            <input type="password"
                                class="form-control  @if ($errors->has('password')) is-invalid @endif" id="password"
                                name="password" placeholder="Type the password here..." value="{{ $random_password }}"
                                required>
                            <button type="button" class="input-group-text" id="password-visibility-toggler"><i
                                    class="bi bi-eye"></i></button>
                        </div>


                        @if ($errors->has('password'))
                            <div class="d-block invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif

                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>

                        <select class="form-select @if ($errors->has('role')) is-invalid @endif"
                            aria-label="City" name="role" required>
                            <option selected disabled>Choose one</option>
                            @foreach ($roles as $id => $role)
                                <option value="{{ $id }}" @if (old('role') == $id) selected @endif>
                                    {{ $role }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('role'))
                            <div class="invalid-feedback">
                                {{ $errors->first('role') }}
                            </div>
                        @endif

                    </div>
                    @yield('branch-select-input')


                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('branches-management.admin-management', ['branch' => $branch->id]) }}"
                        class="btn btn-light me-2"><i class="bi bi-arrow-left-circle me-1"></i>Back</a>

                    <div class="d-flex flex-row">
                        <a href="{{ route('branches-management.admin-management.new', ['branch' => $branch->id]) }}"
                            class="btn btn-light me-2"><i class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                        <button class="btn btn-primary"><i class="bi bi-plus-square me-1"></i></i>Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
