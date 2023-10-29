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
                                        <h3 class="card-title mb-0">App Server</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-dark btn-sm" href="{{ route('createapp-serverview') }}">
                                            <i class="fa fa-plus-circle"></i> <span> Create Server</span>
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
                                                                <th class="wd-15p border-bottom-0">Server ID</th>
                                                                <th class="wd-20p border-bottom-0">Category</th>
                                                                <th class="wd-20p border-bottom-0">Color Code</th>
                                                                <th class="wd-15p border-bottom-0"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($allServers) > 0)
                                                                @php
                                                                    $i = 1;
                                                                @endphp
                                                                @foreach ($allServers as $server)
                                                                    @php
                                                                        $serverId = $server->id;
                                                                        $serverName = $server->server_id;
                                                                        $callingTime = $server->calling_time;
                                                                        $category = $server->category;
                                                                        $authCode = $server->auth_code;
                                                                        $colorScheme = $server->app_color_scheme;
                                                                    @endphp
                                                                    <tr>
                                                                        <td> {{ $i }}</td>
                                                                        <td> {{ $serverName }}</td>
                                                                        <td> {{ $category }}</td>
                                                                        <td> {{ $server->color_name }}</td>
                                                                        <td>
                                                                            <a href="javacript:void(0)"
                                                                                data-toggle="modal"
                                                                                data-target="#editServer{{ $serverId }}">
                                                                                <span class="badge badge-primary">
                                                                                    <i class="fa fa-pencil"></i> Edit
                                                                                </span>
                                                                            </a>

                                                                            <a href="javascript:void(0)"
                                                                                class="deleteBtn"
                                                                                data-info="{{ $server }}">
                                                                                <span class="badge badge-danger">
                                                                                    <i class="fa fa-trash"></i> Delete
                                                                                </span>
                                                                            </a>
                                                                        </td>
                                                                    </tr>

                                                                    <div class="modal fade"
                                                                        id="editServer{{ $serverId }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="normalmodal"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <form method="POST"
                                                                                action="{{ route('update-app-server', ['id' => $serverId]) }}">
                                                                                @method('PUT')
                                                                                @csrf
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="normalmodal1">
                                                                                            <strong>Edit Server
                                                                                                ({{ $serverName }})</strong>
                                                                                        </h5>
                                                                                        <button type="button"
                                                                                            class="close"
                                                                                            data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                            <span
                                                                                                aria-hidden="true">×</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">

                                                                                        <div class="row">
                                                                                            <div class="col-md-6">
                                                                                                <div
                                                                                                    class="form-group mb-2">
                                                                                                    <label
                                                                                                        for="serverId"
                                                                                                        class="form-label">Server
                                                                                                        ID</label>
                                                                                                    <input
                                                                                                        class="form-control form-control-lg"
                                                                                                        name="serverId"
                                                                                                        value="{{ $serverName }}"
                                                                                                        placeholder="Enter server id">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <div
                                                                                                    class="form-group mb-2">
                                                                                                    <label
                                                                                                        for="calling_time"
                                                                                                        class="form-label">Calling
                                                                                                        Time</label>
                                                                                                    <input
                                                                                                        class="form-control form-control-lg"
                                                                                                        name="calling_time"
                                                                                                        value="{{ $callingTime }}"
                                                                                                        placeholder="Enter calling time">
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <div
                                                                                                    class="form-group mb-2">
                                                                                                    <label
                                                                                                        for="category"
                                                                                                        class="form-label">Category</label>
                                                                                                    <select
                                                                                                        name="category"
                                                                                                        class="form-control form-control-lg">
                                                                                                        <option
                                                                                                            value="mtnairtime"
                                                                                                            {{ $category == 'mtnairtime' ? "selected='selected'" : '' }}>
                                                                                                            MTN Airtime
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="mtnsme_gift"
                                                                                                            {{ $category == 'mtnsme_gift' ? "selected='selected'" : '' }}>
                                                                                                            MTN Data
                                                                                                            (SME,
                                                                                                            Gifting)
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="mtndirect"
                                                                                                            {{ $category == 'mtndirect' ? "selected='selected'" : '' }}>
                                                                                                            MTN Direct
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="gloairtime"
                                                                                                            {{ $category == 'gloairtime' ? "selected='selected'" : '' }}>
                                                                                                            Glo Airtime
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="glodata"
                                                                                                            {{ $category == 'glodata' ? "selected='selected'" : '' }}>
                                                                                                            Glo Data
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="etiairtime"
                                                                                                            {{ $category == 'etiairtime' ? "selected='selected'" : '' }}>
                                                                                                            9Mobile
                                                                                                            Airtime
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="etidata"
                                                                                                            {{ $category == 'etidata' ? "selected='selected'" : '' }}>
                                                                                                            9Mobile Data
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="airtelairtime"
                                                                                                            {{ $category == 'airtelairtime' ? "selected='selected'" : '' }}>
                                                                                                            Airtel
                                                                                                            Airtime
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="airteldata"
                                                                                                            {{ $category == 'airteldata' ? "selected='selected'" : '' }}>
                                                                                                            Airtel Data
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <div
                                                                                                    class="form-group mb-2">
                                                                                                    <label
                                                                                                        for="color_scheme"
                                                                                                        class="form-label">Color
                                                                                                        Scheme</label>
                                                                                                    <select
                                                                                                        name="color_scheme"
                                                                                                        class="form-control form-control-lg">
                                                                                                        <option
                                                                                                            value="0"
                                                                                                            {{ $colorScheme == '0' ? "selected='selected'" : '' }}>
                                                                                                            Red</option>
                                                                                                        <option
                                                                                                            value="1"
                                                                                                            {{ $colorScheme == '1' ? "selected='selected'" : '' }}>
                                                                                                            Blue
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="2"
                                                                                                            {{ $colorScheme == '2' ? "selected='selected'" : '' }}>
                                                                                                            Green
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="3"
                                                                                                            {{ $colorScheme == '3' ? "selected='selected'" : '' }}>
                                                                                                            Yellow
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="4"
                                                                                                            {{ $colorScheme == '4' ? "selected='selected'" : '' }}>
                                                                                                            Purple
                                                                                                        </option>
                                                                                                        <option
                                                                                                            value="5"
                                                                                                            {{ $colorScheme == '5' ? "selected='selected'" : '' }}>
                                                                                                            Grey
                                                                                                        </option>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="form-group mb-2">
                                                                                            <label for="auth_code"
                                                                                                class="form-label">Authorization
                                                                                                Code</label>
                                                                                            <input name="auth_code"
                                                                                                class="form-control form-control-lg"
                                                                                                value="{{ $authCode }}">
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

    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.js') }}"></script>
    <script src="{{ asset('assets/js/custom/api.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".deleteBtn").on("click", function() {
            let appInfo = JSON.parse($(this).attr('data-info'));
            let appId = appInfo.id;

            Swal.fire({
                title: 'Are you sure?',
                html: "You want to delete <b>" + appInfo.server_id +
                    "</b>. <br> from your app server. <br> You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/app/' + appId + '/delete';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request cancelled', 'info');
                }
            });
        });
    </script>
