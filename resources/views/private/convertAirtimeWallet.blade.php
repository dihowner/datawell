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

                                    <form method="post" action="{{ route('user.convert-airtime-wallet') }}">
                                        @csrf



                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <div class='form-group mb-4'>
                                                    <label for='quantity' class='form-label'>Airtime To Cash
                                                        Balance:</label>
                                                    <input class='form-control form-control-lg' name='quantity'
                                                        value='{{ $UtilityService::CURRENCY . number_format($userDetail->airtime_cash, 2) }}'
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-group mb-4">
                                                    <label for='quantity' class='form-label'>Airtime To Cash
                                                        Balance:</label>
                                                    <select class="form-control form-control-lg walletType"
                                                        name="walletType">
                                                        <option value="">-- Select Wallet -- </option>
                                                        <option value="main" data-name="main"> Main Wallet</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-4">
                                                <div class='form-group mb-4'>
                                                    <label for='amount' class='form-label'>Enter Amount:</label>
                                                    <input class='form-control form-control-lg amount' name='amount'
                                                        value='0' min='0'>
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

        // vending of NECO...
        $(".payBtn").click(function(e) {
            button = $(this);

            e.preventDefault();
            var walletType = $(".walletType option:selected").attr('data-name');
            var amount = $(".amount").val();
            var transactPin = $(".transactPin").val();

            var load_form = true; //Should form load...?

            if (load_form) {

                if (walletType == undefined || walletType == '' || amount == undefined || amount == '' ||
                    transactPin == undefined || transactPin == '') {
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
                        html: "You are about to convert  <b>N" + numberWithCommas(amount) +
                            "</b> to your " + walletType.toUpperCase() + " wallet.",
                        title: "Convert Airtime",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Proceed',
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
