@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.main.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.main.topnav')
                    @include('components.main.breadcrumb')

                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">

                                    <div class="mb-0">
                                        <h3 class="card-title mb-0">Create API</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    @if ($errors->any() || session()->has('error'))
                                        <div class="alert alert-danger" style="font-size: 18px">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                                @if (session()->has('error'))
                                                    <li>{{ session()->get('error') }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('createapi') }}">
                                        @csrf
                                        <div class="col-md-12">

                                            <div class="form-group mb-2">
                                                <label class="form-label">API Name</label>
                                                <input type="text" name="api_name" placeholder="Enter API name"
                                                    class="form-control form-control-lg">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Select Vendor</label>
                                                <select type="text" name="vendor_id"
                                                    class="form-control form-control-lg fetchVendorInfo">
                                                    <option value="">-- Select Vendor --</option>
                                                    @if (count($allVendors) > 0)
                                                        @foreach ($allVendors as $vendor)
                                                            <option value="{{ $vendor->id }}"
                                                                data-info="{{ $vendor }}">
                                                                {{ $vendor->vendor_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="vendor_info mb-2"></div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Select Delivery Route</label>
                                                <select type="text" name="api_delivery_route"
                                                    class="form-control form-control-lg">
                                                    <option value="">-- Select Delivery Route --</option>
                                                    <option value="instant">Instant Delivery</option>
                                                    <option value="cron">Cron/Delayed Delivery</option>
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <button class="btn btn-danger btn-lg" type="submit"><i
                                                        class="fa fa-plus-circle"></i> Create API</button>
                                            </div>

                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/custom/api.js') }}"></script>

    <script>
        $(".fetchVendorInfo").on("change", function() {
            let vendorId = $(".fetchVendorInfo").val();

            let vendorInfoField = $(".vendor_info");
            if (vendorId == "" || vendorId == undefined) {
                vendorInfoField.html("");
                Swal.fire({
                    title: 'Error',
                    text: "Please select a vendor",
                    icon: 'error'
                })
            } else {
                try {
                    const searchURL = "{{ config('app.url') }}";

                    $.ajax({
                        url: searchURL + "main/api/vendor/" + `${vendorId}`,
                        type: 'GET',
                        beforeSend: function() {
                            vendorInfoField.html(
                                "<i class='fa fa-spinner fa-spin'></i> fetching vendor requirement");
                        },
                        success: function(response) {
                            vendorResult(response, vendorInfoField);
                        },
                        error: function(error, textStatus, errorThrown) {
                            vendorInfoField.html("");
                            let errorResponse;
                            if (error.status === 404) {
                                errorResponse = error.responseJSON.message;
                            } else {
                                // Handle other errors
                                errorResponse = errorThrown;
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Error !",
                                html: errorResponse
                            })
                        }
                    });
                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error !",
                        html: error
                    });
                }
            }
        });
    </script>
