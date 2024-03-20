@include('components.head')

@php
    $decodeConversion = json_decode($conversionSettings, true);
    // Remove "settings" from the array list...
    unset($decodeConversion['settings']);
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

                                    <form method="post" action="{{ route('user.submit-airtime-cash-request') }}">
                                        @csrf

                                        <div class="row mb-2">
                                            @foreach ($decodeConversion as $conversionIndex => $conversionValue)
                                                @if ($conversionValue['status'] == 1)
                                                    <div class="col-sm-3 mb-2">
                                                        <label class="option p-2 d-flex">
                                                            <span class="option-control">
                                                                <span class="radio radio-bold radio-brand">
                                                                    <input type="radio" name="network"
                                                                        value="{{ $conversionIndex }}"
                                                                        data-percent="{{ $conversionValue['percentage'] }}"
                                                                        data-status="{{ $conversionValue['status'] }}"
                                                                        data-receiver="{{ $conversionValue['receiver'] }}">
                                                                    <span></span>
                                                                </span>
                                                            </span>
                                                            <span class="option-label">
                                                                <span class="option-head">
                                                                    <span class="option-focus">
                                                                        {{ number_format($conversionValue['percentage'], 2) }}%
                                                                    </span>
                                                                </span>
                                                                <span class="option-body">
                                                                    <img class="img-fluid"
                                                                        src="{{ asset($conversionValue['image_url']) }}"
                                                                        alt="">
                                                                </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="form-group mb-4 d-none phone_number_section">
                                            <label for="amount" class="form-label">Airtime Receiver </label>
                                            <input class="form-control form-control-lg phone_number" type="text"
                                                name="phone_number" readonly>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="amount" class="form-label">Sent From </label>
                                            <input class="form-control form-control-lg" type="text"
                                                name="airtime_sender" maxlength="11">
                                        </div>

                                        <div class="row">

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Enter Amount:</label>
                                                <input class="form-control form-control-lg amount" name="amount"
                                                    placeholder="Enter amount" type="number" min="1000">
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Amount to Receive: </label>
                                                <input class="form-control form-control-lg amountToPay" type="number"
                                                    value="0.00" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="amount" class="form-label">Additional Note (Optional)</label>
                                            <textarea class="form-control form-control-lg" type="text" name="additional_note"
                                                placeholder="Additional note to admin" rows="4"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-danger mt-4 mb-0">Submit</button>
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

            let airtimeReceiver = $("input[name='network']:checked").attr('data-receiver');
            let sellingPercentage = $("input[name='network']:checked").attr('data-percent');

            $(".phone_number_section").removeClass("d-none");
            $(".phone_number").val(airtimeReceiver);

            let airtimeAmount = $(".amount").val();
            let amountToPay = (airtimeAmount * sellingPercentage) / 100;
            $(".amountToPay").val(amountToPay);
        });

        $(".amount").on('keyup paste', function() {
            let airtimeAmount = $(this).val();

            let networkType = $("input[name='network']:checked").val();

            if (networkType == "" || networkType == undefined) {
                $(this).val(0);
                swal.fire({
                    title: "Error",
                    text: "Select a network",
                    icon: "error"
                })
            } else {
                let sellingPercentage = $("input[name='network']:checked").attr('data-percent');
                let amountToPay = (airtimeAmount * sellingPercentage) / 100;
                $(".amountToPay").val(amountToPay);
            }
        });
    </script>
