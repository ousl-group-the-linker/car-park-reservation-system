@section('summery')
    <div class="row m-0">
        <div class="card p-0">
            <div class="container  p-4">
                <div class="row mb-3">
                    <div class="col-12">
                        <h1>Account Summary</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 d-flex flex-column">
                        <span class="fw-lighter">Current Balance</span>
                        <span class="fs-2">Rs {{ number_format($accountSummery->currentBalance, 2) }}
                    </div>
                    <div class="col-3  d-flex flex-column">
                        <span class="fw-lighter">Available Balance</span>
                        <span class="fs-2">Rs {{ number_format($accountSummery->availableBalance, 2) }}</span>
                    </div>
                    <div class="col-6  d-flex flex-column align-items-end">
                        <span class="fw-lighter">On Hold</span>
                        <span class="fs-2">Rs {{ number_format($accountSummery->onHold, 2) }}</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <a class="btn btn-main text-light" href="{{ route('balance-and-recharge.recharge') }}"><i
                                class="bi bi-wallet-fill me-2">
                            </i>Recharge</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
