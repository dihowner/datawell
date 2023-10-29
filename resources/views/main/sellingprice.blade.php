@include('components.head')
@php
    $request = request()->segments();
    $planId = end($request);
@endphp

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
                            <form method="POST" action="{{ route('update-product-plan', ['id' => $planId]) }}">
                                @csrf @method('PUT')

                                <div class="card">

                                    <div class="card-header d-flex justify-content-between">

                                        <div class="mb-0">
                                            <h3 class="card-title mb-0">Update Product Selling Price</h3>
                                        </div>

                                        <div class="ml-auto mb-0">
                                            <button class="btn btn-default open_disabled_btn" type="button">
                                                <i class="fa fa-pencil"></i> <span>Edit</span>
                                            </button>

                                            <button class="btn btn-dark submit_prices">
                                                <i class="fa fa-paper-plane"></i> <span> Update Pricing</span>
                                            </button>
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

                                        <div class="table-responsive"
                                            style="max-height: 800px;overflow-y: auto;overflow-x: hidden;">
                                            <table
                                                class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                                <thead>
                                                    <tr style="position: sticky;top: -1px;background-color: #ffffff;">
                                                        <th>S/N</th>
                                                        <th>Product</th>
                                                        <th>Cost Price</th>
                                                        <th>Selling Price</th>
                                                        <th>Extra Charges</th>
                                                        <th>Net Selling Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($planProducts) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($planProducts as $planProduct)
                                                            @php
                                                                $productInfo = $planProduct['product'];
                                                                $costPrice = $productInfo['cost_price'];
                                                                $productName = $productInfo['product_name'];
                                                                $categoryName = $productInfo['category']['category_name'];
                                                                $totalPrice = (float) $planProduct->selling_price + $planProduct->extra_charges;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $i }}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center mb-6">
                                                                        <div class="mr-3"
                                                                            style="background-image: url({{ asset($planProduct['image_url']) }}); width: 50px; height: 50px; background-size: cover;">
                                                                        </div>
                                                                        <div>
                                                                            <input type="hidden"
                                                                                value="{{ $planProduct['product_id'] }}"
                                                                                name="productId[]" />
                                                                            <strong>{{ $productName }}</strong>
                                                                            <span
                                                                                class="d-block">{{ $planProduct['product_id'] }}</span>
                                                                            <strong
                                                                                class="d-block text-primary">Category:
                                                                                {{ $categoryName }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="fs-18 font-weight-bold text-primary">{{ $UtilityService::CURRENCY . number_format($costPrice, 2) }}
                                                                </td>
                                                                <td>
                                                                    <input class="form-control selling_price"
                                                                        name="sellingPrice[]"
                                                                        value="{{ $planProduct->selling_price }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control extra_charge"
                                                                        name="extraCharges[]"
                                                                        value="{{ $planProduct->extra_charges }}"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="fs-18 font-weight-bold text-primary net_selling_price">
                                                                        {{ number_format($totalPrice, 2) }}
                                                                    </div>
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
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>

    <script>
        //Calculate if selling percent is entered...
        $(document).on("keyup", ".selling_price", function() {
            var sellingPercent = $(this).parents("tr").find(".selling_price").val();
            sellingPercent = parseFloat(sellingPercent == '' ? 0 : sellingPercent);

            var extraCharge = $(this).parents("tr").find(".extra_charge").val();
            extraCharge = parseFloat(extraCharge == '' ? 0 : extraCharge);

            var netSellingPrice = parseFloat(sellingPercent) + parseFloat(extraCharge);

            var nSpCol = $(this).parents("tr").find(".net_selling_price");
            nSpCol.html('' + numberWithCommas(netSellingPrice.toFixed(2)));
        });

        //Calculate if extra charge is entered...
        $(document).on("keyup", ".extra_charge", function() {
            var sellingPercent = $(this).parents("tr").find(".selling_price").val();
            sellingPercent = parseFloat(sellingPercent == '' ? 0 : sellingPercent);

            var extraCharge = $(this).parents("tr").find(".extra_charge").val();
            extraCharge = parseFloat(extraCharge == '' ? 0 : extraCharge);

            var netSellingPrice = parseFloat(sellingPercent) + parseFloat(extraCharge);

            var nSpCol = $(this).parents("tr").find(".net_selling_price");
            nSpCol.html('' + numberWithCommas(netSellingPrice.toFixed(2)));
        });

        $(document).on("click", ".open_disabled_btn", function() {
            var isReadonly = $('.selling_price').prop('readonly'); //Check if one of the input is readonly or not

            if (isReadonly) {
                $(".selling_price").prop("readonly", false);
                $(".extra_charge").prop("readonly", false);
            } else {
                $(".selling_price").prop("readonly", true);
                $(".extra_charge").prop("readonly", true);
            }
        });

        var load_form = true; //Should form load...?

        $(document).on("click", ".submit_prices", function() {
            let button = $('.submit_prices');
			
	        if(load_form) {
                var form = $(this).parents('form');
                
                Swal.fire({
                    icon: 'question',
                    text: 'Are you sure you want to save? This action is irreversible',
                    showCancelButton: true,
                    confirmButtonText: "Yes, sure",
                    cancelButtonText: "Cancel!",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
			return false;
        });
    </script>
