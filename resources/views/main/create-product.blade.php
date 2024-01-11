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
                                        <h3 class="card-title mb-0">Create Product</h3>
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

                                    <form method="POST" action="{{ route('createproduct') }}">
                                        @csrf
                                        <div class="col-md-12">

                                            <div class="form-group mb-2">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" name="product_name"
                                                    class="form-control form-control-lg"
                                                    placeholder="Enter product name">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Product ID</label>
                                                <input type="text" name="product_id"
                                                    class="form-control form-control-lg" placeholder="Enter product id">
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Select Category</label>
                                                <select name="category_id"
                                                    class="form-control form-control-lg select2-show-search">
                                                    <option value="">-- Select Category --</option>
                                                    @if (count($allCategories) > 0)
                                                        @foreach ($allCategories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="form-label">Cost Price</label>
                                                <input type="text" name="cost_price"
                                                    class="form-control form-control-lg" placeholder="Enter cost price">
                                            </div>

                                            <div class="form-group mb-2">
                                                <button class="btn btn-danger btn-lg" type="submit"><i
                                                        class="fa fa-plus-circle"></i> Create Product</button>
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
    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>
