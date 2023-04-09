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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">My Bookings</li>
                <li class="breadcrumb-item active" aria-current="page">Place Booking</li>
            </ol>
        </nav>
    </div>
@endsection



@section('main-body')
    <div class="container">
        <div class="card" style="max-width: 620px">
            <form action="{{ route('my-bookings.new') }}" method="post" id="new-booking-frm">
                @csrf
                <div class="card-header">
                    Place Booking
                </div>

                <div class="card-body">
                    <p>You are about to place a booking to the following parking lot, please verify and fill in all the
                        fields correctly.</p>

                    @error('account_balance')
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-currency-exchange me-2"></i>{{ $message }}
                        </div>
                    @enderror

                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <div class="container">
                        <div class="row border py-2 px-3 rounded">
                            <div class="col d-flex flex-column">
                                <span class="fw-light">Branch</span>
                                <b class="branch-name">{{ $branch->name }}</b>
                                <p class="m-0 branch-address">{{ $branch->address_text }}</p>
                            </div>
                            <div class="col d-flex flex-column align-items-center justify-content-center">
                                <span class="fs-4 fw-lighter hourly-rate">Rs
                                    {{ number_format($branch->hourly_rate, 2) }}</span>
                                <span>Per Hour</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            <label for="">
                                Vehicle No
                            </label>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col">
                            <input type="text" name="vehicle_no"
                                class="form-control @error('vehicle_no') is-invalid @enderror" placeholder="Vehicle No"
                                aria-label="Vehicle No" value="{{ old('vehicle_no') }}" required>
                            @error('vehicle_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <input type="date" name="arrivel_date"
                                class="form-control @error('arrivel_date') is-invalid @enderror" placeholder="Arrivel Date"
                                aria-label="Arrivel Date" value="{{ old('arrivel_date') }}" required>
                            @error('arrivel_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="time" name="arrivel_time"
                                class="form-control @error('arrivel_time') is-invalid @enderror" placeholder="Arrivel Time"
                                aria-label="Arrivel Time" value="{{ old('arrivel_time') }}" required>
                            @error('arrivel_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                            <input type="date" name="release_date"
                                class="form-control @error('release_date') is-invalid @enderror" placeholder="Release Date"
                                aria-label="Release Date" value="{{ old('release_date') }}" required>
                            @error('release_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="time" name="release_time"
                                class="form-control @error('release_time') is-invalid @enderror" placeholder="Release Time"
                                aria-label="Release Time" value="{{ old('release_time') }}" required>
                            @error('release_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="rounded  border py-2 px-3 mt-4 estimated-fee">
                        <div class="row">
                            <div class="col d-flex flex-column">
                                <span class="fw-light">Estimated Fee</span>
                                <span class="hours">{{ old('total_hours', '-') }}</span>
                                <input type="hidden" name="total_hours" value="{{ old('total_hours', '-') }}">
                            </div>
                            <div class="col d-flex align-items-center justify-content-center">
                                <span class="fs-2 fs-bold fee">{{ old('estimated_fee', '-') }}</span>
                                <input type="hidden" name="estimated_fee" value="{{ old('estimated_fee', '-') }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-check mt-4 mb-4">
                        <input class="form-check-input @error('i_agree') is-invalid @enderror" type="checkbox"
                            name="i_agree" id="i_agree" value="1" @if (old('i_agree') != 0) checked @endif>
                        <label class="form-check-label" for="i_agree">
                            I agree that if I proceed further it will deduct the above-mentioned amount from my account.
                        </label>
                        @error('i_agree')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('find-parking-lot') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-left-circle me-2"></i>Cancel</a>
                    <button class="btn btn-primary"><i class="bi bi-building-add me-2"></i>Place Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('foot')
    <script>
        let hourlyRate = {{ $branch->hourly_rate }};

        $(document).on("change", "[name=arrivel_date]", calculateEstimatedFees);
        $(document).on("change", "[name=arrivel_time]", calculateEstimatedFees);
        $(document).on("change", "[name=release_date]", calculateEstimatedFees);
        $(document).on("change", "[name=release_time]", calculateEstimatedFees);

        function calculateEstimatedFees() {
            let frm = $("#new-booking-frm");
            let estimatedFeeEl = frm.find(".estimated-fee");


            let estimatedArrivel = Date.parse(
                `${frm.find("[name=arrivel_date]").val()} ${frm.find("[name=arrivel_time]").val()}`);
            let releaseArrivel = Date.parse(
                `${frm.find("[name=release_date]").val()} ${frm.find("[name=release_time]").val()}`);

            let hours = (Math.abs(estimatedArrivel - releaseArrivel) / 1000 / 60 / 60).toFixed(0);
            let fee = (hours * hourlyRate).toFixed(2);

            if (hours == 1) {
                estimatedFeeEl.find(".hours").text(`${hours} Hour`);
                estimatedFeeEl.find(".fee").text(`Rs ${fee}/-`);
            } else if (hours > 1) {
                estimatedFeeEl.find(".hours").text(`${hours} Hours`);
                estimatedFeeEl.find(".fee").text(`Rs ${fee}/-`);
            } else {
                estimatedFeeEl.find(".hours").text(`-`);
                estimatedFeeEl.find(".fee").text("Rs 0.00/-");
            }

            estimatedFeeEl.find("[name=total_hours]").val(hours);
            estimatedFeeEl.find("[name=estimated_fee]").val(fee);

            estimatedFeeEl.find("[name=total_hours]").val(`${hours} Hours`);
            estimatedFeeEl.find("[name=estimated_fee]").val(`Rs ${fee}/-`);


        }
    </script>
@endpush
