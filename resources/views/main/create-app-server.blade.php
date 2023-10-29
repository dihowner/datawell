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

                                    <form method="POST" action="{{ route('createapp-server') }}">
                                        @csrf
                                        <div class="col-md-12">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label class="form-label">Server ID</label>
                                                        <input type="text" name="serverId"
                                                            placeholder="Enter Server Id"
                                                            class="form-control form-control-lg">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label class="form-label">Calling Time</label>
                                                        <input type="number" name="calling_time"
                                                            placeholder="Enter Calling Time" value="1"
                                                            min="1" max="5"
                                                            class="form-control form-control-lg">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label class="form-label">Select Category</label>
                                                        <select name="category" class="form-control form-control-lg">
                                                            <option value="">-- Select Category --</option>
                                                            <option value="mtnairtime">MTN Airtime</option>
                                                            <option value="mtnsme_gift">MTN Data (SME, Gifting)</option>
                                                            <option value="mtndirect">MTN Direct</option>
                                                            <option value="gloairtime">Glo Airtime</option>
                                                            <option value="glodata">Glo Data</option>
                                                            <option value="etiairtime">9Mobile Airtime</option>
                                                            <option value="etidata">9Mobile Data</option>
                                                            <option value="airtelairtime">Airtel Airtime</option>
                                                            <option value="airteldata">Airtel Data</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-2">
                                                        <label class="form-label">Select Color Scheme</label>
                                                        <select name="color_scheme"
                                                            class="form-control form-control-lg">
                                                            <option value="">-- Select Color Scheme--</option>
                                                            <option value="0">Yellow</option>
                                                            <option value="1">Red</option>
                                                            <option value="2">Green</option>
                                                            <option value="3">Blue</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Authorization Key</label>
                                                <input name="auth_code" class="form-control form-control-lg">
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
