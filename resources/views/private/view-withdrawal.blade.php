@include('components.head')
@php
    $decodeBank = json_decode($txnRecord['bank_info']);
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

                    <div class="row mt-4">
                        <div class="col-md-4 offset-md-4">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="page-rightheader ml-auto d-lg-flex justify-content-between d-none">
                                        
                                        <div class="mr-5 mb-0">
                                            <h3 class="card-title mb-0">Transaction Details</h3>
                                            <span class="block">{{ $UtilityService->niceDateFormat($txnRecord['created_at']) }}</span>
                                            <span class="d-block">{!! $UtilityService->withdrawalStatusBySpan($txnRecord['status']) !!}</span>
                                        </div>

                                        <div class="ml-5 mb-0">
                                            <a class="btn btn-dark" href="{{ route('user.transactions') }}">
                                                <i class="fa fa-arrow-circle-left"></i> <span> Go Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-5 fs-16">
                                            
                                    <div class="form-group mb-4">
                                        <h6>Description</h6>
                                        <strong class="text-dark d-block">{{ $txnRecord['description'] }}</strong>
                                    </div>
                                    
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
                                    
                                    <div class="row">
                                        <div class="col">
                                            <h6>Bank Name</h6>
                                            <p class="text-muted font-weight-normal">{{ $decodeBank->bank_name }}</p>
                                        </div>

                                        <div class="col">
                                            <h6>Account Name</h6>
                                            <p class="text-muted font-weight-normal">{{ $decodeBank->account_name }}</p>
                                        </div>

                                        <div class="col">
                                            <h6>Account Number</h6>
                                            <p class="text-muted font-weight-normal">{{ $decodeBank->account_number }}</p>
                                        </div>
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
