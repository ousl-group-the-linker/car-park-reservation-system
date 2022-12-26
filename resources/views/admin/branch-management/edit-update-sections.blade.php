@section('form-body')
    @if (session()->has('profile-create-success-message'))
        <div class="alert alert-info alert-dismissible fade show mx-2 mt-2" role="alert">
            {{ session()->get('profile-create-success-message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-body">

        @if (session()->has('branch-update-or-create-success-message'))
            <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                {{ session()->get('branch-update-or-create-success-message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                name="name" placeholder="Branch Name" value="{{ old('name', $branch->name ?? null) }}" required>
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="address_line_1" class="form-label">Address</label>
            <input type="text" class="form-control @if ($errors->has('address_line_1')) is-invalid @endif"
                id="address_line_1" name="address_line_1" placeholder="Address line 1"
                value="{{ old('address_line_1', $branch->address_line_1 ?? null) }}" required>
            @if ($errors->has('address_line_1'))
                <div class="invalid-feedback">
                    {{ $errors->first('address_line_1') }}
                </div>
            @endif

            <input type="text" class="form-control mt-2  @if ($errors->has('address_line_2')) is-invalid @endif"
                id="address_line_2" name="address_line_2" placeholder="Address line 2"
                value="{{ old('address_line_2', $branch->address_line_2 ?? null) }}">
            @if ($errors->has('address_line_2'))
                <div class="invalid-feedback">
                    {{ $errors->first('address_line_2') }}
                </div>
            @endif

            <input type="text" class="form-control mt-2  @if ($errors->has('address_line_3')) is-invalid @endif"
                id="address_line_3" name="address_line_3" placeholder="Address line 3"
                value="{{ old('address_line_3', $branch->address_line_3 ?? null) }}">
            @if ($errors->has('address_line_3'))
                <div class="invalid-feedback">
                    {{ $errors->first('address_line_3') }}
                </div>
            @endif

            <select class="form-select mt-2  @if ($errors->has('address_city')) is-invalid @endif" aria-label="City"
                name="address_city" required>
                <option selected disabled>Choose one</option>
                @foreach ($districts as $district)
                    <option disabled>{{ $district->name }}</option>

                    @foreach ($district->Cities as $city)
                        <option value="{{ $city->id }}" @if (old('address_city', $branch->address_city_id ?? null) == $city->id) selected @endif>
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
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" id="email"
                name="email" placeholder="name@example.com" value="{{ old('email', $branch->email ?? null) }}" required>
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>
        <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" class="form-control  @if ($errors->has('contact_number')) is-invalid @endif"
                id="contact_number" name="contact_number" placeholder="0xx xxx xxxx"
                value="{{ old('contact_number', $branch->contact_number ?? null) }}" required>
            @if ($errors->has('contact_number'))
                <div class="invalid-feedback">
                    {{ $errors->first('contact_number') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="parking_slots" class="form-label">Parking Capacity</label>
            <input type="number" min="1" class="form-control @if ($errors->has('parking_slots')) is-invalid @endif"
                id="parking_slots" name="parking_slots" placeholder="0"
                value="{{ old('parking_slots', $branch->parking_slots ?? null) }}" required>
            @if ($errors->has('parking_slots'))
                <div class="invalid-feedback">
                    {{ $errors->first('parking_slots') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="hourly_rate" class="form-label">Hourly Rate (Rs)</label>
            <input type="number" min="1" class="form-control @if ($errors->has('hourly_rate')) is-invalid @endif"
                id="hourly_rate" name="hourly_rate" placeholder="0"
                value="{{ old('hourly_rate', $branch->hourly_rate ?? null) }}" required>
            @if ($errors->has('hourly_rate'))
                <div class="invalid-feedback">
                    {{ $errors->first('hourly_rate') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Branch Manager</label>

            <div id="branch-manager-input"
                class="bg-white rounded border form-control @if ($errors->has('manager_id')) is-invalid border-danger @endif"
                tabindex="0" style="position: relative">

                @php
                    $managerId = $branch->Manager->id ?? null;
                    $email = $branch->Manager->email ?? null;
                    $name = !isset($branch->Manager) ? null : $branch->Manager->first_name . ' ' . $branch->Manager->last_name;
                    $hasOldManager = old('manager_id', $managerId) !== null && old('manager_name', $name) !== null && old('manager_email', $email) !== null;
                @endphp

                <input type="text" name="manager_id"
                    @if ($hasOldManager) value="{{ old('manager_id', $managerId) }}" @endif
                    style="position: absolute;left:0;top:0;right:0;bottom:0;z-index:-1000">
                <input type="hidden" name="manager_name"
                    @if ($hasOldManager) value="{{ old('manager_name', $name) }}" @endif>
                <input type="hidden" name="manager_email"
                    @if ($hasOldManager) value="{{ old('manager_email', $email) }}" @endif>

                <div class="selected d-flex @if (!$hasOldManager) d-none @endif align-items-center px-3 py-2">
                    <div class="flex-grow-1 me-3">
                        <p class="m-0 name">{{ old('manager_name', $name) }}</p>
                        <p class="m-0"><small class="email">{{ old('manager_email', $email) }}</small></p>
                    </div>
                    <div class="flex-grow-0">
                        <button type="button" class="remove-selected btn btn-danger"><i
                                class="bi bi-x-square-fill"></i></button>
                    </div>
                </div>
                <div
                    class="not-selected d-flex @if ($hasOldManager) d-none @endif align-items-center  px-3 py-2">
                    <div class="flex-grow-1 me-3">
                        <p class="m-0">Not selected</p>
                    </div>
                    <div class="flex-grow-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#choose-manager-model">
                            <i class="bi bi-plus-square-fill"></i>
                        </button>
                    </div>
                </div>

            </div>
            @if ($errors->has('manager_id'))
                <div class="d-block invalid-feedback">
                    {{ $errors->first('manager_id') }}
                </div>
            @endif

        </div>


    </div>
    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('branches-management') }}" class="btn btn-light me-2"><i
                class="bi bi-arrow-left-circle me-1"></i>Back</a>

        <div class="d-flex flex-row">
            @if (isset($branch))
            <a href="{{ route('branches-management.edit', ['branch' => $branch->id]) }}" class="btn btn-light me-2"><i
                    class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                    <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
                    @else
                    <a href="{{ route('branches-management.new') }}" class="btn btn-light me-2"><i
                            class="bi bi-arrow-clockwise me-1"></i>Reset</a>
                <button class="btn btn-primary"><i class="bi bi-plus-square me-1"></i></i>Create</button>
            @endif
        </div>
    </div>
@endsection

@section('choose-manager-model')
    <div id="choose-manager-model" class="modal modal-lg fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Branch Managers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="border-bottom container py-2 px-3">
                        <form id="search-managers-frm">
                            <div class="row">
                                <div class="col-12 col-md-6 mb-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name">
                                </div>
                                <div class="col-12 col-md-6 mb-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="d-flex  justify-content-end">
                                    <button type="submit" id="search-managers-btn" class="btn btn-primary"><i
                                            class="bi bi-search me-2"></i>Search</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="managers-container" class="d-none container py-3 px-4 pb-1">
                    </div>

                    <div id="first-screen" class="d-flex flex-column align-items-center justify-content-center p-3"
                        style="min-height: 450px">
                        <img src="{{ asset('images/ilustrations/lost-online.svg') }}" alt="Lost online image"
                            width="150" class="mb-4">
                        <span class="fs-5 fs-bold">Search Managers</span>
                        <p>Please search by using either the email or name.</p>
                    </div>

                    <div id="no-results-screen"
                        class="d-flex d-none flex-column align-items-center justify-content-center p-3"
                        style="min-height: 450px">
                        <img src="{{ asset('images/ilustrations/feeling-blue.svg') }}" alt="Feeling blue image"
                            width="150" class="mb-4">
                        <span class="fs-5 fs-bold">Ops..</span>
                        <p>No manager found for your query.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('foot')
    <script>

        $("#search-managers-frm").on("submit", function(e) {
            e.preventDefault();

            $("#managers-container").html("");
            $("#first-screen").removeClass("d-none");
            $("#managers-container").addClass("d-none");
            $("#no-results-screen").addClass("d-none");

            let name = $("#search-managers-frm").find("[name=name]").val();
            let email = $("#search-managers-frm").find("[name=email]").val();

            if (String(name).length < 1 && String(email) < 1) {
                return;
            }

            $.get(
                "{{ route('branches-management.search-managers') }}", {
                    name: $("#search-managers-frm").find("[name=name]").val(),
                    email: $("#search-managers-frm").find("[name=email]").val()
                },
                function(data, status) {

                    for (let manager of data) {
                        let template = `
                            <div class="row mb-2 py-2 border rounded align-items-center">
                                <div class="col-12 col-md-5 pe-2">
                                    <p class="m-0">${manager.first_name} ${manager.last_name}</p>
                                    <p class="m-0">
                                        <small>${manager.email}</small> <i class="bi bi-dot me-1"></i>
                                        <small>${manager.city.name}</small> <i class="bi bi-dot me-1"></i>
                                        <small class="${manager.is_activated ? "text-success" : "text-danger"}">${manager.is_activated ? "Active" : "Deactivated"}</small></p>
                                </div>
                                <div class="col-12 col-md-5 pe-2">
                                    <p class="m-0">Branch</p>
                                    <p class="m-0"><small class="badge ${manager.branch == null ? "bg-success" : "bg-secondary"}">${manager.branch == null ? "No Branch" : manager.branch.name}</small></p>
                                </div>
                                <div class="col-12 col-md-2 pe-3 pt-3 pt-md-0 d-flex justify-content-md-end">
                                    <button manager-id="${manager.id}" name="${manager.first_name} ${manager.last_name}"
                                        email="${manager.email}" type="button" class="select-btn btn btn-info" data-bs-dismiss="modal"
                                        ${manager.branch == null ? "" : "disabled"}>
                                        <i class="bi bi-check2-circle me-2"></i>Select</button>
                                </div>
                            </div>`;

                        $("#managers-container").append(template);
                    }

                    if (data.length > 0) {
                        $("#first-screen").addClass("d-none");
                        $("#no-results-screen").addClass("d-none");
                        $("#managers-container").removeClass("d-none");
                    } else {
                        $("#first-screen").addClass("d-none");
                        $("#managers-container").addClass("d-none");
                        $("#no-results-screen").removeClass("d-none");
                    }
                });
        });

    </script>
@endpush
@endsection

@push('foot')
    <script>
        $("#managers-container").on("click", ".select-btn", function() {
            let managerId = $(this).attr("manager-id");
            let name = $(this).attr("name");
            let email = $(this).attr("email");
            $("#branch-manager-input").find("[name=manager_id]").val(managerId);
            $("#branch-manager-input").find("[name=manager_name]").val(name);
            $("#branch-manager-input").find("[name=manager_email]").val(email);

            $("#branch-manager-input").find(".selected .name").text(name);
            $("#branch-manager-input").find(".selected .email").text(email);

            $("#branch-manager-input").find(".selected").removeClass("d-none");
            $("#branch-manager-input").find(".not-selected").addClass("d-none");
        });

        $("#branch-manager-input").on("click", ".remove-selected", function() {
            $("#branch-manager-input").find("[name=manager_id]").val(null);
            $("#branch-manager-input").find("[name=manager_name]").val(null);
            $("#branch-manager-input").find("[name=manager_email]").val(null);

            $("#branch-manager-input").find(".selected .name").text(null);
            $("#branch-manager-input").find(".selected .email").text(null);

            $("#branch-manager-input").find(".selected").addClass("d-none");
            $("#branch-manager-input").find(".not-selected").removeClass("d-none");
        });
    </script>
@endpush
