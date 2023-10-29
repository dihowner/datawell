@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">

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

                    <div class="main-proifle">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="box-widget widget-user">
                                    <div class="widget-user-image d-sm-flex">
                                        <img alt="User Avatar" class="rounded-circle border p-0"
                                            src="/assets/images/users/user-avatar.png" height="150">
                                        <div class="ml-sm-4 mt-4">
                                            <h4 class="pro-user-username mb-3 font-weight-bold">
                                                {{ ucwords($userDetail->fullname) }} <i
                                                    class="fa fa-check-circle text-success"></i></h4>
                                            <div class="d-flex mb-1">
                                                <div class="h6 mb-0 ml-4 mt-1"> <strong>Username: </strong>
                                                    {{ $userDetail->username }} </div>
                                            </div>
                                            <div class="d-flex mb-1">
                                                <div class="h6 mb-0 ml-4 mt-1"> <strong>Phone Number: </strong>
                                                    {{ $userDetail->phone_number }}</div>
                                            </div>
                                            <div class="d-flex mb-1">
                                                <div class="h6 mb-0 ml-4 mt-1"> <strong>Email Address: </strong>
                                                    {{ $userDetail->emailaddress }} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-auto">
                                <div class="text-lg-right mt-4 mt-lg-0">
                                    <a href="{{ route('user.upgrade-plan-view') }}" class="btn btn-danger mb-2"><i
                                            class="si si-paper-plane"></i> Upgrade Plan</a>
                                    <a href="{{ route('user.pin-password-view') }}" class="btn btn-success mb-2"><i
                                            class="si si-lock-open"></i> Change Password</a>
                                    <a href="{{ route('user.bank-account') }}" class="btn btn-white mb-2"><i
                                            class="si si-wallet"></i> Banking Information</a>
                                </div>
                                <div class="mt-5">
                                    <div class="main-profile-contact-list row">
                                        <div class="media col">
                                            <div class="media-icon bg-light text-primary mr-3 mt-1">
                                                <i class="si si-wallet fs-18"></i>
                                            </div>
                                            <div class="media-body">
                                                <small class="text-muted">Wallet Balance</small>
                                                <div class="font-weight-bold fs-18">
                                                    {{ $UtilityService::CURRENCY }}{{ number_format($userDetail->wallet_balance, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media col">
                                            <div class="media-icon bg-light text-primary mr-3 mt-1">
                                                <i class="fa fa-history fs-18"></i>
                                            </div>
                                            <div class="media-body">
                                                <small class="text-muted">Daily Transaction</small>
                                                <div class="font-weight-bold fs-18">
                                                    {{ number_format($userDetail->transactions['daily_total_transaction']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="media col">
                                            <div class="media-icon bg-light text-primary mr-3 mt-1">
                                                <i class="si si-credit-card fs-18"></i>
                                            </div>
                                            <div class="media-body">
                                                <small class="text-muted">Total Transactions</small>
                                                <div class="font-weight-bold fs-18">
                                                    {{ $UtilityService::CURRENCY }}{{ $UtilityService->formatAmount($userDetail->transactions['daily_transaction']->total_sales) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-cover">
                            <div class="wideget-user-tab">
                                <div class="tab-menu-heading p-0">
                                    <div class="tabs-menu1 px-3">

                                    </div>
                                </div>
                            </div>
                        </div><!-- /.profile-cover -->
                    </div>

                </div>
            </div>

        </div>
    </div>
    @include('components.footer-script')
