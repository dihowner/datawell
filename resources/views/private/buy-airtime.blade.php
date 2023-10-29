@include('components.head')

@php
    $airtimeSettings = $UtilityService::airtimeInfo() !== '' ? json_decode($UtilityService::airtimeInfo(), true) : null;
    $minAirtimeAmount = $airtimeSettings !== null ? $airtimeSettings['min_value'] : 0;
@endphp

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

                                    <form method="post" action="{{ route('user.submit-airtime-request') }}">
                                        @csrf

                                        <div class="row mb-2">

                                            @if (count($airtimeProducts) > 0)
                                                @foreach ($airtimeProducts as $airtimeIndex => $airtimeValue)
                                                    <div class="col-sm-3 mb-2">
                                                        <label class="option p-2 d-flex">
                                                            <span class="option-control">
                                                                <span class="radio radio-bold radio-brand">
                                                                    <input type="radio" name="network"
                                                                        class="networkName"
                                                                        value="{{ $airtimeValue['product_id'] }}"
                                                                        data-percent="{{ $airtimeValue['productpricing']['selling_price'] }}"
                                                                        data-status="{{ $airtimeValue['availability'] }}">
                                                                    <span></span>
                                                                </span>
                                                            </span>
                                                            <span class="option-label">
                                                                <span class="option-head">
                                                                    <span class="option-focus">
                                                                        {{ number_format($airtimeValue['productpricing']['selling_price'], 2) }}%
                                                                    </span>
                                                                </span>
                                                                <span class="option-body">
                                                                    <img class="img-fluid"
                                                                        src="{{ asset($airtimeValue['image_url']) }}"
                                                                        alt="">
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="amount" class="form-label">Mobile Number </label>
                                            <input class="form-control form-control-lg phone_number" type="text"
                                                name="phone_number" placeholder="Enter recipient" maxlength="11">
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Enter Amount:</label>
                                                <input class="form-control form-control-lg amount" name="amount"
                                                    placeholder="Enter amount" type="number"
                                                    min="{{ $minAirtimeAmount }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Amount to Pay: </label>
                                                <input class="form-control form-control-lg amountToPay" type="number"
                                                    value="0.00" disabled>
                                                <span class="d-block text-left"></span> <!-- discount price -->
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            @include('components.transact-pin')
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 fs-18">
                                                <b style="color: red">NOTE: </b>Minimum Airtime purchase is
                                                <strong>{{ $UtilityService::CURRENCY . number_format($minAirtimeAmount) }}</strong>
                                            </div>
                                        </div>

                                        <button type="submit"
                                            class="btn btn-danger btn-block btn-lg mt-4 mb-0 vendAirtime">
                                            <i class="fa fa-paper-plane"></i> Buy Airtime
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
    <script>
        $("input[name='network']").on('change', function() {
            $("input[name='network']").parents('label.option').removeClass('selected');
            $("input[name='network']:checked").parents('label.option').addClass('selected');
            $("input[name='network']:checked").prop('checked', true);

            let networkStatus = $("input[name='network']:checked").attr('data-status');

            if (networkStatus == 0) {
                swal.fire({
                    title: "Error",
                    text: "This product is currently experiencing a poor delivery. Kindly try again after some time",
                    icon: "error"
                })
                $("input[name='network']:checked").prop('checked', false);
                $("input[name='network']").parents('label.option').removeClass('selected');
            } else if (networkStatus == 2) {
                swal.fire({
                    title: "Notice",
                    text: "It is important you know that you may experience a slow delivery of this purchase.",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: "Continue",
                    cancelButtonText: "Cancel",
                }).then(function(result) {
                    if (result.isDismissed) {
                        $("input[name='network']:checked").prop('checked', false);
                        $("input[name='network']").parents('label.option').removeClass('selected');
                    }
                });
            }

            // If Network is available or slightly available
            if (networkStatus != 0) {
                // Get the airtime amount probably user has input airtime value before but decided to change it...
                let airtimeAmount = $(".amount").val();
                let networkType = $("input[name='network']:checked").val();
                let sellingPercentage = $("input[name='network']:checked").attr('data-percent');
                sellingPercentage = sellingPercentage <= 0 ? 100 : sellingPercentage;
                let amountToPay = (airtimeAmount * sellingPercentage) / 100;
                $(".amountToPay").val(amountToPay);

                // Amount saved...
                let discount = airtimeAmount - amountToPay;
                $(".amountToPay").siblings('span').html("<em>You saved &#8358;" + discount + "</em>");
            } else {
                $(".amountToPay").val(0);
                $(".amountToPay").siblings('span').html("");
            }
        });

        $(".amount").on('change keyup paste', function() {
            let airtimeAmount = $(this).val();

            let networkType = $("input[name='network']:checked").val();

            if (networkType == "" || networkType == undefined) {
                swal.fire({
                    title: "Error",
                    text: "Select a network",
                    icon: "error"
                })
            } else {
                let sellingPercentage = $("input[name='network']:checked").attr('data-percent');
                sellingPercentage = sellingPercentage <= 0 ? 100 : sellingPercentage;
                let amountToPay = (airtimeAmount * sellingPercentage) / 100;
                $(".amountToPay").val(amountToPay);

                // Amount saved...
                let discount = airtimeAmount - amountToPay;
                $(".amountToPay").siblings('span').html("<em>You saved &#8358;" + discount + "</em>")
            }
        });

        // vending of Airtime...
        $(".vendAirtime").click(function(e) {
            button = $(this);

            e.preventDefault();

            let networkName = $("input[name='network']:checked").val();
            let phoneNumber = $(".phone_number").val();
            let amount_topup = $(".amount").val();
            let chargePrice = $(".amountToPay").val();
            let transactPin = $(".transactPin").val();

            chargePrice = chargePrice ? chargePrice : 0;

            let load_form = true; //Should form load...?

            if (load_form) {

                if (networkName == '' || phoneNumber == '' || amount_topup == '' || transactPin == '') {
                    swal.fire({
                        icon: "info",
                        html: "Please fill all filed before proceeding to make purchase",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if (chargePrice == '' || chargePrice == 0 || chargePrice == undefined) {
                    swal.fire({
                        icon: "info",
                        html: "Please enter a valid airtime value not below or exceeding minimum and maximum airtime",
                        title: "Error",
                        allowOutsideClick: false
                    })
                } else if (transactPin == '' || transactPin == undefined) {
                    swal.fire({
                        icon: "info",
                        html: "Please enter a your transaction pin",
                        title: "Error",
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

                    let form = $(this).parents('form');

                    swal.fire({
                        icon: "question",
                        html: "You are about to purchase <b>" + networkName.toUpperCase() + " N" +
                            amount_topup +
                            "</b> to <b>" + phoneNumber + "</b>. <br> <br> A total of <b>N" +
                            numberWithCommas(chargePrice) + "</b> will be deducted from your wallet",
                        title: "Purchase Airtime",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true
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
