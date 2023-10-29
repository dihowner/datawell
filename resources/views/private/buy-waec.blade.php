@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">

    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    <div class="page-header">
                        <div class="page-leftheader">
                            <h4 class="page-title">{{ $pageTitle }}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">

                                <div class="card-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger" style="font-size: 18px">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @include('components.user-balance')

                                    <div class="col-md-12">
                                        <div class="d-flex">
                                            <img src="{{ asset($waecPackages[0]['image_url']) }}" alt="img"
                                                class="w-10 m-1">
                                        </div>
                                    </div>

                                    <form method="post" action="{{ route('user.submit-education-request') }}">
                                        @csrf

                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <label for="serviceType" class="form-label">Select Service</label>
                                                <select class="form-control form-control-lg serviceType"
                                                    name="serviceType">
                                                    <option value="">-- Select --</option>
                                                    @if (count($waecPackages) > 0)
                                                        @foreach ($waecPackages as $waecProduct)
                                                            @php
                                                                $pricing = $waecProduct['productpricing'];
                                                                $totalPrice = $pricing['selling_price'] + $pricing['extra_charges'];
                                                            @endphp
                                                            <option value="{{ $waecProduct['product_id'] }}" data-name="{{ $waecProduct['product_name'] }}"
                                                                data-price="{{ $totalPrice }}">
                                                                {{ $waecProduct['product_name'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class='form-group mb-4'>
                                                    <label for='amount' class='form-label'>Amount:</label>
                                                    <input class='form-control form-control-lg amountToPay'
                                                        value='0' disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <div class='form-group mb-4'>
                                                    <label for='quantity' class='form-label'>Quantity:</label>
                                                    <input class='form-control form-control-lg' name='quantity'
                                                        value='1' readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group mb-4">
                                                    @include('components.transact-pin')
                                                </div>
                                            </div>

                                        </div>

                                        <button type="submit"
                                            class="btn btn-danger btn-block btn-lg mt-4 mb-0 payBtn">Submit</button>
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

    <script src="{{ asset('assets/js/custom/purchase.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
    <script>
        $(".serviceType").on('change', function() {
            let selectedVolume = $(".serviceType option:selected");

            if (selectedVolume.val() == "") {
                $(".amountToPay").val(0);
                swal.fire({
                    icon: "error",
                    title: "Error",
                    html: "Please select a service type"
                })
            } else {
                let amountPay = selectedVolume.attr('data-price');
                $(".amountToPay").val(amountPay);
            }
        });

        // vending of WAEC...
        $(".payBtn").click(function(e) {
            button = $(this);

            e.preventDefault();
            var serviceName = $(".serviceType option:selected").attr('data-name');
            var transactPin = $(".transactPin").val();

            var load_form = true; //Should form load...?

            if (load_form) {

                if (serviceName == undefined || serviceName == '') {
                    swal.fire({
                        icon: "info",
                        html: "Please fill all filed before proceeding to make purchase",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if (transactPin == "0000") {
                    swal.fire({
                        icon: "info",
                        html: "Default transaction PIN (0000) cannot be used in making transaction",
                        title: "Error",
                        allowOutsideClick: false
                    })
                } else {
                    var form = $(this).parents('form');

                    let amountToPay = $(".amountToPay").val();

                    swal.fire({
                        icon: "question",
                        html: "You are about to buy 1pieces of <b>" + serviceName.toUpperCase() + "</b><br> <br> Total of <b>N" + numberWithCommas(amountToPay) +
                            "</b> will be deducted from your wallet",
                        title: "Make Payment",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Purchase',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                            button.html("Please wait <i class='fa fa-spinner fa-spin'></i>").prop(
                                "disabled", true);
                        }
                    });
                }
            }
            return false;

        });
    </script>
