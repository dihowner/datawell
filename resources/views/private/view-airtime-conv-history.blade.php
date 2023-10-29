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
                                            <h3 class="card-title mb-0">Conversion Details</h3>
                                            <span class="block">{{ $UtilityService->niceDateFormat($txnRecord['created_at']) }}</span>
                                            <div class="d-block">{!! $UtilityService->walletStatusBySpan($txnRecord['status']) !!}</div>
                                        </div>

                                        <div class="ml-5 mb-0">
                                            <a class="btn btn-dark" href="{{ route('user.airtimeconv-history') }}">
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
                                            <h6>Sold At</h6>
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
                                        <span class="d-block"><strong>Reference: </strong> {{ $txnRecord['reference'] }}</span>
                                    </div>
                                    
                                    @if($txnRecord['airtime_reciever'] != "" AND $txnRecord['airtime_reciever'] != "")
                                        <div class="form-group mb-4">
                                            <h6>Sent To</h6>
                                            <span class="d-block">{{ $txnRecord['airtime_reciever'] }}</span>
                                        </div>

                                        <div class="form-group mb-4">
                                            <h6>Sent From</h6>
                                            <span class="d-block">{{ $txnRecord['airtime_sender'] }}</span>
                                        </div>
                                    @endif
                                    
                                    @if ($txnRecord['additional_note'] != NULL)
                                        <div class="form-group border-top">
                                            <h6 class="mt-2">Note To Admin</h6>
                                            <p class="text-muted font-weight-normal">{{ $txnRecord['additional_note'] }}</p>
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
