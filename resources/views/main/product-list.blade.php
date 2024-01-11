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
                                        <h3 class="card-title mb-0">Product Management</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-danger" href="{{ route('editproduct-view') }}">
                                            <i class="fa fa-pencil"></i> <span>Edit</span>
                                        </a>

                                        <a class="btn btn-dark" href="{{ route('createproduct-view') }}">
                                            <i class="fa fa-plus-circle"></i> <span> Create Product</span>
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

                                    <div class="form-group p-3 mb-3 mt-3">
                                        <form method="GET" action="{{ route('search-product') }}" class="ml-5">
                                            <div class="row form-group p-3">
                                                <div class="col-sm-6 col-xs-8">
                                                    <input type="text" name="query"
                                                        class="form-control form-control-lg mb-2" name="query"
                                                        value="{{ request()->query('query') }}"
                                                        placeholder="Search for...">
                                                </div>

                                                <div class="col-sm-2 col-xs-4">
                                                    <button class="btn btn-danger btn-lg btn-block" type="submit"><i
                                                            class="fa fa-search"></i>Search</button>
                                                </div>

                                                <div class="col-sm-2 col-xs-4">
                                                    <a href="{{ route('product-list') }}"
                                                        class="btn btn-dark btn-lg btn-block"><i
                                                            class="fa fa-refresh"></i> Reset</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-12">
                                        <h4>Total Products : {{ $totalProduct }} </h4>
                                    </div>

                                    <div class="table-responsive mb-2">
                                        <table
                                            class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Product</th>
                                                    <th>Category</th>
                                                    <th>Cost Price</th>
                                                    <th>Availability</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if ($allProduct != null)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($allProduct as $product)
                                                        @php
                                                            $costPrice = $product['cost_price'];
                                                            $productName = $product['product_name'];
                                                            $categoryName = $product['category']['category_name'];
                                                        @endphp

                                                        @if (strpos(strtolower($categoryName), 'airtime') !== false or strpos(strtolower($productName), 'paid') !== false)
                                                            @php
                                                                $costPrice = number_format($costPrice, 2) . '%';
                                                            @endphp
                                                        @else
                                                            @php
                                                                $costPrice = $UtilityService::CURRENCY . number_format($costPrice, 2);
                                                            @endphp
                                                        @endif

                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center mb-6">
                                                                    <div class="mr-3"
                                                                        style="background-image: url({{ asset($product['image_url']) }}); width: 50px; height: 50px; background-size: cover;">
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $productName }}</strong>
                                                                        <div class="d-block">
                                                                            {{ $product['product_id'] }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $categoryName }}</td>
                                                            <td>{{ $costPrice }}</td>
                                                            <td>{!! $UtilityService->productAvailability($product->availability) !!}</td>
                                                            <td>
                                                                <a href="javascript:void(0)" class="deleteBtn"
                                                                    data-id="{{ $product->id }}"
                                                                    data-name="{{ $productName }}">
                                                                    <span class="badge badge-danger">
                                                                        <i class="fa fa-trash"></i>
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
                                        @if ($allProduct != null)
                                            {{ $allProduct->links('components.custom-paginator') }}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".deleteBtn").on("click", function() {
            let productId = $(this).attr('data-id');
            let productName = $(this).attr('data-name');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete " + productName + ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/product/' + productId + '/delete';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Your product is safe', 'info');
                }
            });

        });
    </script>
