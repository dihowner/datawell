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

                                    <form method="post" action="{{ route('user.submit-cabletv-request') }}">
                                        @csrf

                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <label for="packageOption" class="form-label">Select Bouquet</label>
                                                <select class="form-control form-control-lg packageOption"
                                                    name="packageOption">
                                                    <option value="">-- Select --</option>
                                                    @if (count($startimesPackages) > 0)
                                                        @foreach ($startimesPackages as $startimesProduct)
                                                            @php
                                                                $pricing = $startimesProduct['productpricing'];
                                                                $totalPrice = $pricing['selling_price'] + $pricing['extra_charges'];
                                                            @endphp
                                                            <option value="{{ $startimesProduct['product_id'] }}"
                                                                data-price="{{ $totalPrice }}"
                                                                data-name="{{ $startimesProduct['product_name'] }}">
                                                                {{ $startimesProduct['product_name'] }}
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
                                                <div class="form-group mb-4">
                                                    <label for="amount" class="form-label">Smartcard Number:</label>
                                                    <input class="form-control form-control-lg smartcard_no"
                                                        name="smartcard_no" placeholder="Enter Smartcard Number"
                                                        maxlength="11">
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group mb-4">
                                                    @include('components.transact-pin')
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4 verification_result"></div>



                                        <button type="submit" class="btn btn-danger btn-block btn-lg mt-4 mb-0 payBtn"
                                            disabled>
                                            <i class="fa fa-paper-plane"></i> Subscribe Decoder
                                        </button>
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
        const verificationEndpoint = "{{ config('app.url') }}verify-cable-tv";

        $(".packageOption").on('change', function() {
            let selectedVolume = $(".packageOption option:selected");

            if (selectedVolume.val() == "") {
                $(".amountToPay").val(0);
                swal.fire({
                    icon: "error",
                    title: "Error",
                    html: "Please select a package option"
                })
            } else {
                let amountPay = selectedVolume.attr('data-price');
                $(".amountToPay").val(amountPay);
            }
        });

        $(document).on('focusout', '.smartcard_no', async function() {
            let smartCard = $(this).val();
            let selectedPackageOption = $(".packageOption option:selected").val();

            if (selectedPackageOption == "") {
                swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Please select package option"
                })
            } else {
                if (smartCard.length > 7) {
                    try {
                        const verifyData = {
                            "category": "cabletv",
                            "service": "startimes",
                            "smart_number": smartCard
                        };
                        verifyCableTv_MeterNo(verificationEndpoint, verifyData, $(".verification_result"), $(
                            ".payBtn"))
                    } catch (error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error !",
                            html: error
                        });
                    }
                }
            }
        });

        // vending of CableTv...
        $(".payBtn").click(function(e) {
            button = $(this);

            e.preventDefault();

            var packageName = $(".packageOption option:selected").attr('data-name');
            var dataprice = $(".packageOption option:selected").attr('data-price');
            var smartNumber = $(".smartcard_no").val();
            var transactPin = $(".transactPin").val();

            var load_form = true; //Should form load...?

            if (load_form) {

                if (packageName == undefined || smartNumber == '' || transactPin == '') {
                    swal.fire({
                        icon: "info",
                        html: "Please fill all filed before proceeding to make purchase",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if (dataprice == '' || dataprice == undefined) {
                    swal.fire({
                        icon: "info",
                        html: "No valid price given for this product(" + packageName +
                            ")Please contact Admin",
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

                    swal.fire({
                        icon: "question",
                        html: "You are about to subscribe <b>" + packageName.toUpperCase() + "</b> on <b>" +
                            smartNumber + "</b>. <br> <br> Total of <b>N" + numberWithCommas(dataprice) +
                            "</b> will be deducted from your wallet",
                        title: "Subscribe",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Subscribe',
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
