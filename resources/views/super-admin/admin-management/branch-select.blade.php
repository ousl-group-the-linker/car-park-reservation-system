@section('branch-select')
    <div id="choose-manager-model" class="modal modal-lg fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Branches</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="border-bottom container py-2 px-3">
                        <form id="search-branch-frm">
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

                    <div id="branches-container" class="d-none container py-3 px-4 pb-1">
                    </div>

                    <div id="first-screen" class="d-flex flex-column align-items-center justify-content-center p-3"
                        style="min-height: 450px">
                        <img src="{{ asset('images/ilustrations/lost-online.svg') }}" alt="Lost online image" width="150"
                            class="mb-4">
                        <span class="fs-5 fs-bold">Search Branch</span>
                        <p>Please search by using either the email or name.</p>
                    </div>

                    <div id="no-results-screen"
                        class="d-flex d-none flex-column align-items-center justify-content-center p-3"
                        style="min-height: 450px">
                        <img src="{{ asset('images/ilustrations/feeling-blue.svg') }}" alt="Feeling blue image"
                            width="150" class="mb-4">
                        <span class="fs-5 fs-bold">Ops..</span>
                        <p>No branches found for your query.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('foot')
        <script>
            $("#search-branch-frm").on("submit", function(e) {
                e.preventDefault();

                $("#branches-container").html("");
                $("#first-screen").removeClass("d-none");
                $("#branches-container").addClass("d-none");
                $("#no-results-screen").addClass("d-none");

                let name = $("#search-branch-frm").find("[name=name]").val();
                let email = $("#search-branch-frm").find("[name=email]").val();

                if (String(name).length < 1 && String(email) < 1) {
                    return;
                }

                $.get(
                    "{{ route('super-admin.admin-management.search-brancges') }}", {
                        name: $("#search-branch-frm").find("[name=name]").val(),
                        email: $("#search-branch-frm").find("[name=email]").val()
                    },
                    function(data, status) {

                        for (let branch of data) {
                            console.log(branch);
                            let template = `
                        <div class="row mb-2 py-2 border rounded align-items-center">
                            <div class="col-12 col-md-5 pe-2">
                                <p class="m-0">${branch.name}</p>
                                <p class="m-0">
                                    <small>${branch.email}</small> <i class="bi bi-dot me-1"></i>
                                    <small>${branch.city.name}</small>
                            </div>
                            <div class="col-12 col-md-5 pe-2">
                                <p class="m-0">Manager</p>
                                <p class="m-0"><small class="badge ${branch.manager == null ? "bg-success" : "bg-secondary"}">${branch.manager == null ? "No Manager" : branch.manager.first_name + " " + branch.manager.last_name}</small></p>
                            </div>
                            <div class="col-12 col-md-2 pe-3 pt-3 pt-md-0 d-flex justify-content-md-end">
                                <button branch-id="${branch.id}" name="${branch.name}"
                                    email="${branch.email}" city="${branch.city.name}" type="button" class="select-btn btn btn-info" data-bs-dismiss="modal"
                                    ${branch.manager == null ? "" : "disabled"}>
                                    <i class="bi bi-check2-circle me-2"></i>Select</button>
                            </div>
                        </div>`;

                            $("#branches-container").append(template);
                        }

                        if (data.length > 0) {
                            $("#first-screen").addClass("d-none");
                            $("#no-results-screen").addClass("d-none");
                            $("#branches-container").removeClass("d-none");
                        } else {
                            $("#first-screen").addClass("d-none");
                            $("#branches-container").addClass("d-none");
                            $("#no-results-screen").removeClass("d-none");
                        }
                    });
            });
        </script>
    @endpush
@endsection

@section('branch-select-input')
    <div class="mb-3">
        <label for="role" class="form-label">Associated Branch</label>

        <div id="branch-select-input"
            class="bg-white rounded border form-control @if ($errors->has('branch_id')) is-invalid border-danger @endif"
            tabindex="0" style="position: relative">


            @php
                $hasOldManager = old('branch_id') !== null && old('branch_name') !== null && old('branch_email') !== null && old('branch_city') !== null;
            @endphp

            <input type="text" name="branch_id"
                @if ($hasOldManager) value="{{ old('branch_id') }}" @endif
                style="position: absolute;left:0;top:0;right:0;bottom:0;z-index:-1000">
            <input type="hidden" name="branch_name"
                @if ($hasOldManager) value="{{ old('branch_name') }}" @endif>
            <input type="hidden" name="branch_email"
                @if ($hasOldManager) value="{{ old('branch_email') }}" @endif>
            <input type="hidden" name="branch_city"
                @if ($hasOldManager) value="{{ old('branch_city') }}" @endif>

            <div class="selected d-flex @if (!$hasOldManager) d-none @endif align-items-center px-3 py-2">
                <div class="flex-grow-1 me-3">
                    <p class="m-0 name">{{ old('branch_name') }}</p>
                    <p class="m-0"><small class="email">{{ old('branch_email') }}</small> <i class="bi bi-dot mx-1"></i> <small class="branch_city">{{ old('branch_city') }}</small></p>
                </div>
                <div class="flex-grow-0">
                    <button type="button" class="remove-selected btn btn-danger"><i
                            class="bi bi-x-square-fill"></i></button>
                </div>
            </div>
            <div class="not-selected d-flex @if ($hasOldManager) d-none @endif align-items-center  px-3 py-2">
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

        @if ($errors->has('branch_id'))
            <div class="d-block invalid-feedback">
                {{ $errors->first('branch_id') }}
            </div>
        @endif

    </div>

    @push('foot')
        <script>


            $("#branches-container").on("click", ".select-btn", function() {
                let branchId = $(this).attr("branch-id");
                let name = $(this).attr("name");
                let email = $(this).attr("email");
                let city = $(this).attr("city");
                $("#branch-select-input").find("[name=branch_id]").val(branchId);
                $("#branch-select-input").find("[name=branch_name]").val(name);
                $("#branch-select-input").find("[name=branch_email]").val(email);
                $("#branch-select-input").find("[name=branch_city]").val(city);

                $("#branch-select-input").find(".selected .name").text(name);
                $("#branch-select-input").find(".selected .email").text(email);
                $("#branch-select-input").find(".selected .branch_city").text(city);

                $("#branch-select-input").find(".selected").removeClass("d-none");
                $("#branch-select-input").find(".not-selected").addClass("d-none");
            });



            $("#branch-select-input").on("click", ".remove-selected", function() {
                $("#branch-select-input").find("[name=branch_id]").val(null);
                $("#branch-select-input").find("[name=branch_name]").val(null);
                $("#branch-select-input").find("[name=branch_email]").val(null);

                $("#branch-select-input").find(".selected .name").text(null);
                $("#branch-select-input").find(".selected .email").text(null);

                $("#branch-select-input").find(".selected").addClass("d-none");
                $("#branch-select-input").find(".not-selected").removeClass("d-none");
            });
        </script>
    @endpush
@endsection
