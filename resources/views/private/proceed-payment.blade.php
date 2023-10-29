@include('components.head')
@php
    $userMeta = $userDetail->user_meta;
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
                            <h4 class="page-title">Proceed To Payment</h4>
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

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Description: &nbsp;</strong> {{ $walletHistories->description }}
                                    </div>

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Amount: &nbsp;</strong>
                                        {{ $UtilityService::CURRENCY }}{{ number_format($walletHistories->amount, 2) }}
                                    </div>

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Amount To Pay: &nbsp;</strong>
                                        {{ $UtilityService::CURRENCY }}{{ number_format($walletHistories->amount_vat_inclusive, 2) }}
                                        <span class="badge badge-info ml-2">VAT Inclusive</span>
                                    </div>

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Reference: &nbsp;</strong> {{ $walletHistories->reference }}
                                    </div>

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Date Created: &nbsp;</strong>
                                        {{ $UtilityService->niceDateFormat($walletHistories->created_at, 'date_time') }}
                                    </div>

                                    <div class="d-sm-flex border p-2 mb-2 fs-18">
                                        <strong>Payment Method: &nbsp;</strong>
                                        {{ Str::ucfirst($walletHistories->channel) }}
                                    </div>

                                    @if ($walletHistories->channel === 'bank')
                                        @php
                                            $bankingInformation = $UtilityService::bankInformation();
                                            $bankAccountInformation = isset($bankingInformation['account_information']) ? $bankingInformation['account_information'] : null;
                                        @endphp
                                        @if ($bankAccountInformation != null)
                                            <div class="border p-2 mb-2 fs-18">
                                                <strong class="h4">Payment should be made to the following account
                                                    details below:</strong>
                                                <span class="d-block">{!! nl2br($bankAccountInformation) !!}</span>
                                            </div>
                                        @endif
                                        <a href="{{ route('user.fund-wallet-view') }}" class="btn btn-danger">
                                            <i class="fe fe-arrow-left-circle"></i> Go Back
                                        </a>
                                    @elseif ($walletHistories->channel === 'monnify')
                                        @if (isset($userMeta['monnify']))
                                            <strong class="h4 d-block mb-2 mt-2 ml-2">Payment should be made to the
                                                following account details below:</strong>
                                            <div class="border p-2 mb-2 fs-18">
                                                <span class="border-bottom d-block mb-3">
                                                    <strong>Account Name: </strong> {{ $userDetail->username }}
                                                </span>
                                                <span class="border-bottom d-block h4 text-danger">Account Number(s)
                                                </span>
                                                @foreach ($userMeta['monnify'] as $bankName => $accountNo)
                                                    <span class="border-bottom d-block mb-3">
                                                        <strong> {{ $bankName }} - </strong> {{ $accountNo }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            <a href="{{ route('user.fund-wallet-view') }}" class="btn btn-danger">
                                                <i class="fe fe-arrow-left-circle"></i> Go Back
                                            </a>
                                        @else
                                            <a class="btn btn-danger" href="{{ route('user.generate-va') }}">
                                                <b> <i class="fa fa-paper-plane-o"></i> Generate Virtual Account</b>
                                            </a>
                                        @endif
                                    @elseif ($walletHistories->channel === 'paystack')
                                        <a href="{{ route('user.generate-paystack-link', ['id' => $walletHistories->reference]) }}"
                                            class="btn btn-danger">
                                            <i class="fe fe-credit-card"></i> Make Payment
                                        </a>
                                    @elseif ($walletHistories->channel === 'flutterwave')
                                        <a href="{{ route('user.generate-flutterwave-link', ['id' => $walletHistories->reference]) }}"
                                            class="btn btn-danger">
                                            <i class="fe fe-credit-card"></i> Make Payment
                                        </a>
                                    @endif

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
    </script>
