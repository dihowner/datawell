<style>
    .side-menu>li>a>i {
        width: 35px;
        line-height: 28px;
        font-size: 1.1rem;
        display: inline-block;
        vertical-align: middle;
        color: #475F7B;
        text-align: center;
        border-radius: 10px;
        margin-right: 8px;
        margin-left: -8px;
        background-color: #e6ecf3;
    }

    .side-menu__label {
        font-size: .8rem;
    }
</style>

<div class="app-sidebar app-sidebar2">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ route('user.index') }}">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo" alt="Datawell">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img dark-logo" alt="Datawell">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img mobile-logo" alt="Datawell">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img darkmobile-logo"
                alt="Datawell">
        </a>
    </div>
</div>
<aside class="app-sidebar app-sidebar3">
    <div class="app-sidebar__user" style="display: none">
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                <img src="{{ asset('assets/images/users/user-avatar.png') }}" alt="user-img"
                    class="avatar-xl rounded-circle mb-1">
            </div>
            <div class="user-info">
                <h5 class=" mb-1 font-weight-bold">{{ Str::ucfirst($userDetail->username) }}</h5>
                <strong class="app-sidebar__user-name text-sm fs-18">Balance:
                    {{ $UtilityService::CURRENCY }}{{ number_format($userDetail->wallet_balance, 2) }}</strong>
            </div>
        </div>
    </div>

    <ul class="side-menu">

        <li class="slide mb-2">
            <a href="{{ route('user.index') }}" class="side-menu__item">
                <i class="fa fa-home"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>

        <li class="slide mb-2">
            <a href="{{ route('user.buy-airtime') }}" class="side-menu__item">
                <i class="fa fa-mobile"></i>
                <span class="side-menu__label">Buy Airtime</span>
            </a>
        </li>

        <li class="slide mb-2">
            <a href="{{ route('user.datamenu') }}" class="side-menu__item">
                <i class="fa fa-wifi"></i>
                <span class="side-menu__label">Buy Data Bundle</span>
            </a>
        </li>

        <li class="slide mb-2">
            <a href="{{ route('user.cabletv-menu') }}" class="side-menu__item">
                <i class="fa fa-tv"></i>
                <span class="side-menu__label">Buy Cable Tv </span>
            </a>
        </li>

        <li class="slide mb-2">
            <a class="side-menu__item" data-toggle="slide" href="javascript:void">
                <i class="fa fa-book"></i>
                <span class="side-menu__label">Education Payment</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{ route('user.buy-waec') }}"><span>WAEC Bills</span></a></li>
                <li><a class="slide-item" href="{{ route('user.buy-neco') }}"><span>NECO Bills</span></a></li>
            </ul>
        </li>

        <li class="slide mb-2">
            <a href="{{ route('user.electricity-menu') }}" class="side-menu__item">
                <i class="fa fa-lightbulb-o"></i>
                <span class="side-menu__label">Electricity Bills</span>
            </a>
        </li>

        <li class="slide mb-2">
            <a class="side-menu__item" data-toggle="slide" href="javascript:void">
                <i class="fa fa-money"></i>
                <span class="side-menu__label">Airtime to Cash </span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{ route('user.airtime-to-cash') }}"><span>Convert Airtime To
                            Cash</span></a></li>
                {{-- <li><a class="slide-item" href="{{ route('user.bank-withdrawal') }}"><span>Withdraw To Bank</span></a></li> --}}
                <li><a class="slide-item" href="{{ route('user.airtimeconv-history') }}"><span>Airtime Conversion
                            History</span></a></li>
                {{-- <li><a class="slide-item" href="{{ route('user.withdrawals-history') }}"><span>Cash Withdrawal
                            History</span></a></li> --}}
            </ul>
        </li>

        <li class="slide mb-2">
            <a class="side-menu__item" data-toggle="slide" href="javascript:void">
                <i class="fa fa-credit-card"></i>
                <span class="side-menu__label">My Wallet</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{ route('user.fund-wallet-view') }}"><span>Fund Wallet</span></a></li>
                <li><a class="slide-item" href="{{ route('user.convert-airtimewallet-view') }}"><span>Convert Airtime
                            Cash
                            to Wallet </span></a></li>
                <li><a class="slide-item" href="{{ route('user.share-wallet-view') }}"><span>Transfer Fund to
                            Member</span></a></li>
                <li><a class="slide-item" href="{{ route('user.wallet-history') }}"><span>Wallet History</span></a>
                </li>
            </ul>
        </li>

        <li class="slide mb-2">
            <a class="side-menu__item" data-toggle="slide" href="javascript:void">
                <i class="fa fa-user"></i>
                <span class="side-menu__label">My Account</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{ route('user.profile') }}"><span>My Profile</span></a></li>
                {{-- <li><a class="slide-item" href="{{ route('user.profile') }}"><span>My Profile</span></a></li> --}}
                <li><a class="slide-item" href="{{ route('user.pin-password-view') }}"><span>Change Pin /
                            Password</span></a></li>
                <li><a class="slide-item" href="{{ route('user.bank-account') }}"><span>Bank Account</span></a></li>
                <li><a class="slide-item" href="{{ route('user.upgrade-plan-view') }}"><span>Upgrade Your
                            Plan</span></a></li>
            </ul>
        </li>

        <li class="slide mb-2">
            <a href="{{ route('user.transactions') }}" class="side-menu__item">
                <i class="fa fa-history"></i>
                <span class="side-menu__label">Transaction History</span>
            </a>
        </li>

    </ul>
    <div class="app-sidebar-help">
        <div class="dropdown text-center">
            <div class="help d-flex">
                {{-- <a href="index-2.html#" class="nav-link p-0 help-dropdown" data-toggle="dropdown">
                    <span class="font-weight-bold">Help Info</span> <i class="fa fa-angle-down ml-2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow p-4">
                    <div class="border-bottom pb-3">
                        <h4 class="font-weight-bold">Help</h4>
                        <a class="text-primary d-block" href="index-2.html#">Knowledge base</a>
                        <a class="text-primary d-block" href="index-2.html#">Contact@info.com</a>
                        <a class="text-primary d-block" href="index-2.html#">88 8888 8888</a>
                    </div>
                    <div class="border-bottom pb-3 pt-3 mb-3">
                        <p class="mb-1">Your Fax Number</p>
                        <a class="font-weight-bold" href="index-2.html#">88 8888 8888</a>
                    </div>
                    <a class="text-primary" href="{{ route('user.sign-out') }}">Logout</a>
                </div>
                <div class="ml-auto">
                    <a class="nav-link icon p-0" href="index-2.html#">
                        <svg class="header-icon" x="1008" y="1248" viewBox="0 0 24 24"  height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false"><path opacity=".3" d="M12 6.5c-2.49 0-4 2.02-4 4.5v6h8v-6c0-2.48-1.51-4.5-4-4.5z"></path><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-11c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2v-5zm-2 6H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6zM7.58 4.08L6.15 2.65C3.75 4.48 2.17 7.3 2.03 10.5h2a8.445 8.445 0 013.55-6.42zm12.39 6.42h2c-.15-3.2-1.73-6.02-4.12-7.85l-1.42 1.43a8.495 8.495 0 013.54 6.42z"></path></svg>
                        <span class="pulse "></span>
                    </a>
                </div> --}}
                <div class="m-auto f-18">
                    <a href="{{ route('user.sign-out') }}">
                        <strong class="d-block">
                            <i class="fa fa-sign-out"></i> Logout ({{ strtolower($userDetail->username) }})
                        </strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</aside>
