@extends('layouts.side-bar')
@include('common.side-bar.side-bar')

@section('sidebar-body')
    @yield('common-side-bar')
@endsection

@section('main-header')
    <div class="header d-flex flex-column justify-content-center">
        <h1 class="fs-4 m-0">
            <i class="bi bi-p-circle me-2 text-main"></i></i>&nbsp;Find Parking
        </h1>
    </div>
@endsection



@section('main-body')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="fs-5 m-0">Search Parking Lot</h2>
            </div>
            <form action="{{ route('find-parking-lot') }}" method="get">
                @if (session()->has('profile-update-success-message'))
                    <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
                        {{ session()->get('profile-update-success-message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>

                                    <select class="form-select  @if ($errors->has('status')) is-invalid @endif"
                                        aria-label="City" name="status">
                                        <option selected>Any</option>
                                        <option>Available</option>
                                        <option>Not Available</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('status') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>

                                    <select class="form-select  @if ($errors->has('city')) is-invalid @endif"
                                        aria-label="City" name="city">
                                        <option selected>Any</option>
                                        @foreach ($districts as $district)
                                            <option disabled>{{ $district->name }}</option>

                                            @foreach ($district->Cities as $city)
                                                <option value="{{ $city->id }}"
                                                    @if (old('city', Auth::user()->city_id) == $city->id) selected @endif>
                                                    &nbsp;&nbsp;&nbsp;{{ $city->name }}</option>
                                            @endforeach

                                            <option disabled></option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('city'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('city') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('find-parking-lot') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            @foreach ($branches as $branch)
                <div class="col-12 col-md-4 branch">
                    <div class="d-flex flex-column bg-white rounded p-4 border mb-4">
                        <h3 class="text-truncate-container max-lines-2">{{ $branch->name }}
                            #{{ $branch->id }}</h3>

                        <p><i class="bi bi-geo-alt-fill me-2"></i>{{ $branch->address_text }}</p>

                        <span><i class="bi bi-envelope-at-fill me-2"></i>{{ $branch->email }}</span>
                        <span><i class="bi bi-telephone-fill me-2"></i>{{ $branch->contact_number }}</span>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <span><small>{{ $branch->reservedSlots()->count() }} / {{ $branch->parking_slots }} Slots
                                        Available</small></span>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                        aria-valuenow="{{ $branch->reservedPersentage() }}" aria-valuemin="0"
                                        aria-valuemax="{{ 100 }}"
                                        style="width: {{ $branch->reservedPersentage() }}%"></div>
                                </div>
                            </div>
                            <a href="{{route('my-bookings.new', ['branch' => $branch->id])}}" class="btn btn-main place-booking">Place Booking</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-end">
            {{ $branches->links() }}
        </div>
    </div>

    <div class="modal fade" id="place-booking-model" aria-hidden="true" aria-labelledby="place-booking-model-label"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('my-bookings.new') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="place-booking-model-Label">Place a booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to place a booking to the following parking lot, please verify and fill in all the
                            fields correctly.</p>

                        <input type="hidden" name="branch">
                        <input type="hidden" name="hourly_rate">
                        <div class="container">
                            <div class="row border py-2 px-3 rounded">
                                <div class="col d-flex flex-column">
                                    <span class="fw-light">Branch</span>
                                    <b class="branch-name">Car Park No #01</b>
                                    <p class="m-0 branch-address">42/22, Main Street, Gampaha</p>
                                </div>
                                <div class="col d-flex flex-column align-items-center justify-content-center">
                                    <span class="fs-4 fw-lighter hourly-rate">-</span>
                                    <span>Per Hour</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <label for="">
                                    Expected Arrivel Date & Time
                                </label>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <input type="date" name="arrivel_date" class="form-control" placeholder="Arrivel Date"
                                    aria-label="Arrivel Date">
                            </div>
                            <div class="col">
                                <input type="time" name="arrivel_time" class="form-control"
                                    placeholder="Arrivel Time" aria-label="Arrivel Time">
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col">
                                <label for="">
                                    Expected Release Date & Time
                                </label>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <input type="date" name="release_date" class="form-control"
                                    placeholder="Release Date" aria-label="Release Date">
                            </div>
                            <div class="col">
                                <input type="time" name="release_time" class="form-control"
                                    placeholder="Release Time" aria-label="Release Time">
                            </div>
                        </div>

                        <div class="rounded  border py-2 px-3 mt-4 estimated-fee">
                            <div class="row">
                                <div class="col d-flex flex-column">
                                    <span class="fw-light">Estimated Fee</span>
                                    <span class="hours">-</span>
                                </div>
                                <div class="col d-flex align-items-center justify-content-center">
                                    <span class="fs-2 fs-bold fee">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light">Cancel</button>
                        <button class="btn btn-primary">Place Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="place-booking-confirm-model" aria-hidden="true"
        aria-labelledby="place-booking-confirm-model-label" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('my-bookings.new') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="place-booking-confirm-model-Label">Confirmation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <h4 class="alert-heading">You are about to place a booking!</h4>
                            <p>Verify the following informations before place the booking.</p>
                            <hr>
                            <p class="mb-0">Once the booking has been
                                placed you won't be able to modify it.</p>
                        </div>

                        <input type="hidden" name="branch">

                        <div class="d-flex flex-column border py-2 px-3 rounded mt-2">
                            <span class="fw-light">Branch</span>
                            <b>Car Park No #01</b>
                            <p class="m-0">42/22, Main Street, Gampaha</p>
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <label for="">
                                    Expected Arrivel Date & Time
                                </label>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <input type="date" name="arrivel_date" class="form-control"
                                    placeholder="Arrivel Date" aria-label="Arrivel Date" disabled>
                            </div>
                            <div class="col">
                                <input type="time" name="arrivel_time" class="form-control"
                                    placeholder="Arrivel Time" aria-label="Arrivel Time" disabled>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col">
                                <label for="">
                                    Expected Release Date & Time
                                </label>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col">
                                <input type="date" name="release_date" class="form-control"
                                    placeholder="Release Date" aria-label="Release Date" disabled>
                            </div>
                            <div class="col">
                                <input type="time" name="release_time" class="form-control"
                                    placeholder="Release Time" aria-label="Release Time" disabled>
                            </div>
                        </div>
                        <div class="form-check mt-4 mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                I agree that if I proceed further it will deduct the above-mentioned amount from my account.
                            </label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light">Cancel</button>
                            <button type="submit" class="btn btn-primary">Place Booking</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
