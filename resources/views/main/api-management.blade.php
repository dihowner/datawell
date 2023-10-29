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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">

                                    <div class="mb-0">
                                        <h3 class="card-title mb-0">API Management</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-dark btn-sm" href="{{ route('createapi-view') }}">
                                            <i class="fa fa-plus-circle"></i> <span> Create</span>
                                        </a>
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

                                    <div class="table-responsive">
                                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered table-striped" id="example1">
                                                        <thead>
                                                            <tr>
                                                                <th class="wd-15p border-bottom-0">S/N</th>
                                                                <th class="wd-15p border-bottom-0">Api Name</th>
                                                                <th class="wd-20p border-bottom-0">Vendor</th>
                                                                <th class="wd-15p border-bottom-0"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($allApis) > 0)
                                                                @php
                                                                    $i = 1;
                                                                @endphp
                                                                @foreach ($allApis as $allApi)
                                                                    @php
                                                                        $apiId = $allApi->id;
                                                                        $apiName = $allApi->api_name;
                                                                    @endphp
                                                                    <tr role="row" class="odd">
                                                                        <td class="sorting_1">{{ $i }}</td>
                                                                        <td>
                                                                            <strong>{{ $allApi->api_name }}</strong>
                                                                            <span class="d-flex"><strong
                                                                                    class="text-info">Delivery Method:
                                                                                </strong>
                                                                                {{ ucwords($allApi->api_delivery_route) }}</span>
                                                                        </td>
                                                                        <td>{{ $allApi->vendor->vendor_name }}</td>
                                                                        <td>

                                                                            <a href="javascript:void(0)" class="fetchModal"
                                                                                data-info="{{ $allApi }}">
                                                                                <span class="badge badge-dark">
                                                                                    <i class="fa fa-pencil"></i> Modify
                                                                                </span>
                                                                            </a>

                                                                            <a href="javascript:void(0)" class="deleteBtn"
                                                                                data-info="{{ $allApi }}">
                                                                                <span class="badge badge-danger">
                                                                                    <i class="fa fa-trash"></i> Delete
                                                                                </span>
                                                                            </a>
                                                                        </td>
                                                                    </tr>

                                                                    @php
                                                                        $i++;
                                                                    @endphp
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script')
    @include('components.modals.edit-api')

    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/js/custom/api.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".fetchModal").on("click", function() {
            const apiInfo = JSON.parse($(this).attr('data-info'));
            let apiId = apiInfo.id;

            const searchURL = "{{ config('app.url') }}";
            $(this).find('span').html("Please wait <i class='fa fa-spinner fa-spin'>");
            
            $.ajax({
                url: searchURL + "main/api/" + `${apiId}`,
                type: 'GET',
                success: function(response) {
                    $(this).find('span').html("<i class='fa fa-pencil'></i> Modify");
                    console.log(response)
                    displayModal(response);
                    $(".fetchModal").find('span').html("<i class='fa fa-pencil'></i> Modify");
                }
            });
        });

        $(".fetchVendorInfo").on("change", function() {
            // Should vendor change, we need to fetch the vendor info
            let vendorId = $(this).val();

            const searchURL = "{{ config('app.url') }}";
            $.ajax({
                url: searchURL + "main/api/vendor/" + `${vendorId}`,
                type: 'GET',
                success: function(response) {
                    modifyModalVendorRequirement(response);
                }
            });

        });

        $(".deleteBtn").on("click", function() {
            let apiInfo = JSON.parse($(this).attr('data-info'));
            let apiId = apiInfo.id;

            // let amountDeposited = $(this).attr('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                html: "You want to delete <b>" + apiInfo.api_name +
                    "</b>. <br> Service relying on this API won't be available for purchase. <br> You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/api/' + apiId + '/delete';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request cancelled', 'info');
                }
            });
        });
    </script>
