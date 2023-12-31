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
                            <h4 class="page-title">Welcome <strong class="text-danger">{{ $userDetail->username }}!
                                </strong>
                                Welcome to The Data Well
                            </h4>
                            <em class="fs-20">...making data cheaper and affordable.</em>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <strong class="text-black fs-20">Wallet Balance</strong>
                                    <h4 class="mb-1 fs-35 font-weight-bold">
                                        {{ $UtilityService::CURRENCY . number_format($userDetail->wallet_balance, 2) }}
                                    </h4>
                                    <h5 class="mt-2 text-muted">
                                        <a href="{{ route('user.fund-wallet-view') }}">
                                            <span class="text-danger"><i class="fa fa-wallet"></i>Fund Wallet</span></a>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <strong class="text-black fs-20">Airtime To Cash Balance</strong>
                                    <h4 class="mb-1 fs-35 font-weight-bold">
                                        {{ $UtilityService::CURRENCY . number_format($userDetail->airtime_cash, 2) }}
                                    </h4>
                                    <h5 class="mt-2 text-muted">
                                        <a href="{{ route('user.convert-airtimewallet-view') }}">
                                            <span class="text-danger"><i class="fas fa-wallet"></i> Convert</span>
                                        </a> ||
                                        <a href="{{ route('user.bank-withdrawal') }}">
                                            <span class="text-danger"><i class="fa fa-wallet"></i>Withdraw</span>
                                        </a>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <strong class="text-black fs-20">Access Package</strong>
                                    <h4 class="mb-1 fs-35 font-weight-bold">{{ $userDetail->plan->plan_name }}</h4>
                                    <h5 class="mt-2 text-muted">
                                        <a href="{{ route('user.upgrade-plan-view') }}">
                                            <span class="text-danger"><i class="fa fa-wallet"></i>Upgrade your access
                                                package</span></a>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card">
                                <div class="card-body text-center">
                                    <strong class="text-black fs-20">Support Representative</strong>
                                    <h4 class="mb-1 fs-35 font-weight-bold">07063420657</h4>
                                    <h5 class="mt-2 text-muted">
                                        <span class="text-danger">
                                            <a
                                                href="https://api.whatsapp.com/send/?phone=%2B2347063420657&text=Hi Datawell, I need your attention">
                                                WhatsApp
                                            </a>
                                            / <a href="tel:09033024846">Call Us</a></span>
                                    </h5>
                                </div>
                            </div>
                        </div>

                        @isset($userMeta['monnify'])
                            @foreach ($userMeta['monnify'] as $bankName => $accountNo)
                                <div class="col-xl-3 col-lg-6 col-md-12">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <strong class="text-black fs-20">{{ $bankName }}</strong>
                                            <h4 class="mb-1 fs-35 font-weight-bold">{{ $accountNo }}</h4>
                                            <h5 class="mt-2 text-muted">
                                                <span class="text-danger">Instant wallet funding approval</span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endisset

                        <div class="col-xl-12 col-md-12 col-lg-12">
                            <div class="row">
                                <div class="col-xl-7 col-lg-7 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><strong>My Account Number</strong></h5>
                                            <p class="card-text">
                                                To instantly credit your DataWell account, you can deposit or transfer
                                                funds using various payment methods,
                                                including the <b>Website Channel, Mobile app, USSD, ATM, or POS</b>, to
                                                your unique account number.
                                                It's possible to make payments of any amount at any time. <br> <br> <br>

                                                Please keep in mind that there is a fee of 1% capped at #50 for payments
                                                made to your DataWell account number. <br>
                                                <strong>
                                                    For example, if you make a payment of #1000, a fee of #10 will be
                                                    deducted. If you make a payment of #5000,
                                                    the fee will be #50, as it is the maximum amount charged. Payments
                                                    above #5000 will have a flat charge of #50 only
                                                </strong>
                                                @if (isset($userMeta['monnify']))

                                                    <div class="col-md-12 mt-3 mr-1 fs-25">
                                                        <span class="border-bottom d-block mb-3">
                                                            <strong>Account Name: </strong> {{ $userDetail->username }}
                                                        </span>
                                                        @foreach ($userMeta['monnify'] as $bankName => $accountNo)
                                                            <span class="border-bottom d-block mb-3">
                                                                <strong>Account Number: </strong> {{ $accountNo }} -
                                                                {{ $bankName }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            @if (!isset($userMeta['monnify']))
                                                <a class="btn btn-danger" href="{{ route('user.generate-va') }}">
                                                    <b> <i class="fa fa-paper-plane-o"></i> Generate Virtual Account</b>
                                                </a>
                                            @else
                                                <a class="btn btn-danger" href="{{ route('user.fund-wallet-view') }}">
                                                    <b> <i class="si si-wallet"></i> Fund My Wallet</b>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-5 col-lg-5 col-md-12">
                                    @include('components.quick-link')
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('components.footer-script')
