@include('components.head')
@php
    $userMeta = $userDetail->user_meta;
    $bankingInformation = $UtilityService::bankInformation();
    $bankAccountInformation = isset($bankingInformation['account_information']) ? $bankingInformation['account_information'] : null;

    // Bank charges condition...
    $stampDutyInformation = isset($bankingInformation['bank_charges']) ? json_decode($bankingInformation['bank_charges'], true) : null;
    $stampDutyCharge = $stampDutyInformation != null ? $stampDutyInformation['stamp_duty_charge'] : 0;
    $minFundingAmount = $stampDutyInformation != null ? $stampDutyInformation['min_wallet'] : 0;
    $minStampAmount = $stampDutyInformation != null ? $stampDutyInformation['min_stamp'] : 0;

    $flutterwaveSetting = $UtilityService::flutterwaveInfo() === false ? false : $UtilityService::flutterwaveInfo();
    if ($flutterwaveSetting !== false) {
        $flutterwaveSetting = json_decode($flutterwaveSetting, true);
        $isflutterActive = $flutterwaveSetting['status'];
        $flutterPublicKey = $flutterwaveSetting['public_key'];
        $flutterSecretKey = $flutterwaveSetting['secret_key'];
    }

    $paystackSetting = $UtilityService::paystackInfo() === false ? false : $UtilityService::paystackInfo();
    if ($paystackSetting !== false) {
        $paystackSetting = json_decode($paystackSetting, true);
        $ispaystackActive = $paystackSetting['status'];
        $paystackPublicKey = $paystackSetting['public_key'];
        $paystackSecretKey = $paystackSetting['secret_key'];
    }
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
                            <h4 class="page-title">Fund My Wallet</h4>
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

                                    <form method="post" action="{{ route('user.create-wallet-request') }}">
                                        @csrf
                                        <div class="">
                                            <div class="form-group mb-4">
                                                <label for="amount" class="form-label">Amount:</label>
                                                <input class="form-control form-control-lg" name="amount"
                                                    placeholder="Enter amount" type="number"
                                                    min="{{ $minFundingAmount }}"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                                <span class="fs-15">Minimum wallet funding is <strong
                                                        class="text-danger">{{ $UtilityService::CURRENCY }}{{ number_format($minFundingAmount) }}</strong></span>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label for="funding_method" class="form-label">Payment Method:</label>
                                                <select class="form-control form-control-lg mb-4 funding_method"
                                                    name="funding_method">
                                                    <option value="">-- Select Method --</option>
                                                    <option value="manual_funding">Bank Deposit (Transfer)</option>
                                                    @if ($isflutterActive === 'active')
                                                        <option value="flutterwave">Flutterwave Online Payment</option>
                                                    @endif

                                                    @if ($ispaystackActive === 'active')
                                                        <option value="paystack">Paystack Online Payment</option>
                                                    @endif

                                                    <option value="instant_funding">Instant Funding (Recommended)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12 d-none bank-details">
                                                <div class="p-5 border fs-18">
                                                    <b style="color: red">NOTE: </b>All payment are to be made to the
                                                    following account details below
                                                    <span class="d-block">{!! nl2br($bankAccountInformation) !!}</span>

                                                    <div class="displayNotifyStampDuty d-none">
                                                        <br>
                                                        <strong class="text-danger">Please Note; </strong>
                                                        Funding through Bank Deposit or Transfer from
                                                        <span
                                                            class='text-danger'>{{ $UtilityService::CURRENCY }}{{ number_format($minStampAmount, 2) }}</span>
                                                        attract a funding charge of
                                                        {{ $UtilityService::CURRENCY }}{{ number_format($stampDutyCharge) }}
                                                    </div>
                                                </div>
                                            </div>
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

    <script>
        $(".funding_method").on("change", function() {
            let fundMethod = $(this).val();
            if (fundMethod == "manual_funding") {
                $(".bank-details").removeClass("d-none");
            } else {
                $(".bank-details").addClass("d-none");
            }
        });

        $("input[name='amount']").on("keyup keypress keydown", function() {
            let newAmount = parseFloat($(this).val());
            let minStampAmount = parseFloat({{ $minStampAmount }})
            if (newAmount >= minStampAmount) {
                $(".displayNotifyStampDuty").removeClass('d-none')
            } else {
                $(".displayNotifyStampDuty").addClass('d-none')
            }
        });
    </script>
