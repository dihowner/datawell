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
                                        <h3 class="card-title mb-0">Create Plans</h3>
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

                                    <form method="POST" action="{{ route('createplan') }}">
                                        @csrf
                                        <div class="col-md-12">

                                            <div class="form-group mb-2">
                                                <label class="form-label">Plan Name</label>
                                                <input type="text" name="plan_name"
                                                    class="form-control form-control-lg" placeholder="Enter plan name">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Plan Amount</label>
                                                <input type="text" name="upgrade_fee"
                                                    class="form-control form-control-lg" placeholder="Enter plan name">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label for="serviceType" class="form-label">Description</label>
                                                <textarea class="form-control form-control-lg" name="plan_description" row="5"
                                                    placeholder="Enter plan description"></textarea>
                                            </div>

                                            <div class="form-group mb-2">
                                                <button class="btn btn-danger btn-lg" type="submit"><i
                                                        class="fa fa-plus-circle"></i> Create Plan</button>
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
