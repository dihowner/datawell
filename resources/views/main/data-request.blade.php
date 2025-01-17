@include('components.head')
<link href="/assets/plugins/select2/select2.min.css" rel="stylesheet" />

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
                                        <h3 class="card-title mb-0">Data Bundle Requests</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-dark" href="javascript:void(0)" data-toggle="modal"
                                            data-target="#createProductModal">
                                            <i class="fa fa-plus-circle"></i> <span> Create Request</span>
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

                                    <div class="table-responsive mb-2">
                                        <table
                                            class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Product</th>
                                                    <th>Init Code</th>
                                                    <th>Wrap Code</th>
                                                    <th>MobileNig</th>
                                                    <th>Smeplug</th>
                                                    <th>Ipay</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($dataRequests) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($dataRequests as $dataRequest)
                                                        @php
                                                            $productName = isset($dataRequest->product->product_name) ? $dataRequest->product->product_name : '';
                                                            $requestId = $dataRequest->id;

                                                            $mobileNigCode = $dataRequest->mobilenig;
                                                            $smeplugCode = $dataRequest->smeplug;
                                                            $ipayCode = $dataRequest->ipay;
                                                            $initCode = $dataRequest->init_code;
                                                            $wrapCode = $dataRequest->wrap_code;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center mb-6">
                                                                    <div class="mr-3"
                                                                        style="background-image: url({{ asset($dataRequest['image_url']) }}); width: 50px; height: 50px; background-size: cover;">
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $productName }}</strong>
                                                                        <div class="d-block">
                                                                            {{ $dataRequest->product_id }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $initCode }} </td>
                                                            <td>{{ $wrapCode }} </td>
                                                            <td>{{ $mobileNigCode }} </td>
                                                            <td>{{ $smeplugCode }} </td>
                                                            <td>{{ $ipayCode }} </td>
                                                            <td>

                                                                <a href="javacript:void(0)" data-toggle="modal"
                                                                    data-target="#editRequest{{ $requestId }}">
                                                                    <span class="badge badge-info">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </span>
                                                                </a>

                                                                <a href="javascript:void(0)" class="deleteBtn"
                                                                    data-id="{{ $requestId }}"
                                                                    data-name="{{ $productName }}">
                                                                    <span class="badge badge-danger">
                                                                        <i class="fa fa-trash"></i>
                                                                    </span>
                                                                </a>

                                                            </td>
                                                        </tr>

                                                        <div class="modal fade" id="editRequest{{ $requestId }}"
                                                            tabindex="-1" role="dialog" aria-labelledby="normalmodal"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form method="POST"
                                                                    action="{{ route('update-data-request', ['id' => $requestId]) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="normalmodal1">
                                                                                <strong>Edit Request
                                                                                    ({{ $productName }})
                                                                                </strong>
                                                                            </h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="row">

                                                                                <div class="col-md-6 mb-2">
                                                                                    <label class="form-label">Init
                                                                                        Code</label>
                                                                                    <input
                                                                                        class="form-control form-control-lg"
                                                                                        name="init_code"
                                                                                        value="{{ $initCode }}"
                                                                                        placeholder="Enter init code">
                                                                                </div>

                                                                                <div class="col-md-6 mb-2">
                                                                                    <label class="form-label">Wrap
                                                                                        Code</label>
                                                                                    <input
                                                                                        class="form-control form-control-lg"
                                                                                        name="wrap_code"
                                                                                        value="{{ $wrapCode }}"
                                                                                        placeholder="Enter wrap code">
                                                                                </div>

                                                                                <div class="col-md-6 mb-2">
                                                                                    <label class="form-label">MobileNig
                                                                                        Code</label>
                                                                                    <input
                                                                                        class="form-control form-control-lg"
                                                                                        name="mobilenig"
                                                                                        value="{{ $mobileNigCode }}"
                                                                                        placeholder="Enter mobilenig code">
                                                                                </div>

                                                                                <div class="col-md-6 mb-2">
                                                                                    <label class="form-label">Smeplug
                                                                                        Code</label>
                                                                                    <input
                                                                                        class="form-control form-control-lg"
                                                                                        name="smeplug"
                                                                                        value="{{ $smeplugCode }}"
                                                                                        placeholder="Enter smeplug code">
                                                                                </div>

                                                                                <div class="col-md-6 mb-2">
                                                                                    <label class="form-label">Ipay
                                                                                        Code</label>
                                                                                    <input
                                                                                        class="form-control form-control-lg"
                                                                                        name="ipay"
                                                                                        value="{{ $ipayCode }}"
                                                                                        placeholder="Enter ipay code">
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Save
                                                                                changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $i++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                        @if (count($dataRequests) > 0)
                                            {{ $dataRequests->links('components.custom-paginator') }}
                                        @endif

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
    @include('components.modals.data-request')

    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".deleteBtn").on("click", function() {
            let requestId = $(this).attr('data-id');
            let productName = $(this).attr('data-name');
            Swal.fire({
                title: 'Are you sure?',
                html: "You want to delete " + productName + " Data Request code. Purchase of service for " +
                    productName + " won't be available. <br> You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/data-request/' + requestId + '/delete';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Your data request code is safe', 'info');
                }
            });

        });
    </script>
