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
                                        <h3 class="card-title mb-0">Edit Cost Price</h3>
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

                                    <div class="form-group p-3 mb-3 mt-3">
                                        <form method="GET" action="{{ route('get-category-product') }}">
                                            <div class="row form-group p-3">
                                                <div class="col-sm-6 col-xs-8">
                                                    <select name="category_id" class="form-control form-control-lg select2-show-search">
                                                        <option value="">-- Select Category --</option>
                                                        @if (count($allCategories) > 0)
                                                            @foreach ($allCategories as $category)
                                                                <option value="{{ $category->id }}" 
                                                                    {{ request()->get("category_id") == $category->id ? "selected='selected'" : '' }}
                                                                >
                                                                    {{ $category->category_name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-sm-2 col-xs-4">
                                                    <button class="btn btn-danger btn-lg btn-block" type="submit"><i
                                                            class="fa fa-search"></i>Search</button>
                                                </div>

                                                <div class="col-sm-2 col-xs-4">
                                                    <a href="{{ route('editproduct-view') }}" class="btn btn-dark btn-lg btn-block"><i
                                                        class="fa fa-refresh"></i> Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <form method="POST" action="{{ route('update-cost-price') }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="table-responsive mb-5"
                                            style="max-height: 800px;overflow-y: auto;overflow-x: hidden;">
                                            <table
                                                class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                                <thead style="position: sticky;top: -1px;background-color: #ffffff;">
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Product Name</th>
                                                        <th>Category</th>
                                                        <th>Cost Price</th>
                                                        <th>Availability</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($products) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($products as $product)
                                                            <tr>
                                                                <td>{{ $i }}
                                                                </td>
                                                                <td>
                                                                    <strong>{{ $product->product_name }}</strong>
                                                                    <span
                                                                        class="d-block text-danger">{{ $product->product_id }}</span>
                                                                </td>
                                                                <td>{{ $product->category->category_name }}</td>
                                                                <td>
                                                                    <input class="form-control" name="costPrice[]"
                                                                        value="{{ $product->cost_price }}">
                                                                    <input class="form-control" name="id[]"
                                                                        type="hidden" value="{{ $product->id }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control" name="availability[]"
                                                                        value="{{ $product->cost_price }}">
                                                                        <option value="1" {{ $product->availability == '1' ? "selected='selected'" : '' }}>Available</option>
                                                                        <option value="2" {{ $product->availability == '2' ? "selected='selected'" : '' }}>Slow Delivery</option>
                                                                        <option value="0" {{ $product->availability == '0' ? "selected='selected'" : '' }}>Blocked / Downtime</option>
                                                                    </select>
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

                                        @if (count($products) > 0)
                                            <div class="row form-group mb-2 justify-content-center">


                                                <div class="col-sm-2 col-xs-4">
                                                    <button class="btn btn-danger btn-block" type="submit"><i
                                                            class="fa fa-paper-plane"></i>
                                                        Modify 
                                                    </button>
                                                </div>

                                                <div class="col-sm-2 col-xs-4">
                                                    <a href="{{ route('product-list') }}" class="btn btn-dark btn-block"><i
                                                        class="fa fa-backward"></i> Back To Products</a>
                                                </div>
                                            </div>
                                        @endif

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
