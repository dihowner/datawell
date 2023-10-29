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
                    
                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-4">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="page-rightheader ml-auto d-lg-flex justify-content-between d-none">
                                        
                                        <div class="mr-5 mb-0">
                                            <h3 class="card-title mb-0">Transaction Details</h3>
                                            <span class="block">{{ $UtilityService->niceDateFormat($txnRecord['created_at']) }}</span>
                                            <span class="d-block">{!! $UtilityService->txStatusBySpan($txnRecord['status']) !!}</span>
                                        </div>

                                        <div class="ml-5 mb-0">
                                            <a class="btn btn-dark" href="{{ route('user.transactions') }}">
                                                <i class="fa fa-arrow-circle-left"></i> <span> Go Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-5 fs-16">
                                    
                                    <div class="row">
                                        <div class="col">
                                            <h6>Old Wallet Balance</h6>
                                            <p class="text-muted font-weight-normal">{{ $UtilityService::CURRENCY.number_format($txnRecord['old_balance'], 2) }}</p>
                                        </div>

                                        <div class="col">
                                            <h6>Amount Debited</h6>
                                            <p class="text-muted font-weight-normal">{{ $UtilityService::CURRENCY.number_format($txnRecord['amount'], 2) }}</p>
                                        </div>

                                        <div class="col">
                                            <h6>New Wallet Balance</h6>
                                            <p class="text-muted font-weight-normal">{{ $UtilityService::CURRENCY.number_format($txnRecord['new_balance'], 2) }}</p>
                                        </div>
                                    </div>
                                            
                                    <div class="form-group mb-4">
                                        <h6>Description</h6>
                                        <strong class="text-dark d-block">{{ $txnRecord['description'] }}</strong>
                                        <span class="d-block"><strong>Category: </strong> {{ Str::ucfirst($txnRecord['category']) }}</span>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        @if($txnRecord['destination'] != NULL)
                                            <h6>Destination</h6>
                                            <span class="d-block">{{ $txnRecord['destination'] }}</span>
                                        @endif
                                        <span class="d-block"><strong>Reference: </strong> {{ $txnRecord["reference"] }}</span>
                                    </div>

                                    @if($txnRecord['pin_details'] != NULL)
                                        <div class="form-group mb-4">
                                            @php
                                                $decodePin = json_decode($txnRecord['pin_details']);
                                            @endphp
                                            @if(isset($decodePin->serial_number))
                                                <strong class="d-block">Serial Number: {{ $decodePin->serial_number }}</strong>
                                            @endif
                                            <strong class="d-block">Pin: {{ $UtilityService->splitNumber($decodePin->pin) }}</strong>
                                        </div>
                                    @endif

                                    @if($txnRecord['token_details'] != NULL)
                                        <div class="form-group mb-4">
                                            @php
                                                $decodeToken = json_decode($txnRecord['token_details']);
                                            @endphp
                                            <strong class="d-block"><strong>Token: </strong> {{ $UtilityService->splitNumber($decodeToken->token) }}</strong>
                                            @if(isset($decodeToken->unit))
                                                <span class="d-block"><strong>Unit: </strong> {{ $UtilityService->splitNumber($decodeToken->unit) }}</span>
                                            @endif
                                        </div>
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
