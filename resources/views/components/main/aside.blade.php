<style>
    .side-menu > li > a > i {
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
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img darkmobile-logo" alt="Datawell">
            </a>
        </div>
    </div>
    <aside class="app-sidebar app-sidebar3">
        {{-- <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="user-pic">
                    <img src="{{ asset('assets/images/users/user-avatar.png') }}" alt="user-img" class="avatar-xl rounded-circle mb-1">
                </div>
                <div class="user-info">
                    <h5 class=" mb-1 font-weight-bold fs-18">{{ Str::ucfirst($adminUser->fullname) }}</h5>
                    <strong class="d-block text-danger fs-14">{{ $adminUser->roles->role_type }}</strong>
                </div>
            </div>
        </div> --}}
    
        <ul class="side-menu">
    
            <li class="slide mb-2">
                <a href="{{ route('admin-dashboard') }}" class="side-menu__item">
                    <i class="fa fa-home"></i>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>
    
            <li class="slide mb-2">
                <a class="side-menu__item"  data-toggle="slide" href="javascript:void">
                    <i class="fa fa-book"></i>
                    <span class="side-menu__label">System MGT</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a class="slide-item"  href="{{ route('system-settings') }}"><span>System Settings</span></a></li>
                    <li><a class="slide-item"  href="{{ route('planlist') }}"><span>Plan Management</span></a></li>
                    <li><a class="slide-item"  href="{{ route('product-list') }}"><span>Product Management</span></a></li>
                    <li><a class="slide-item"  href="{{ route('payment-history') }}"><span>Payment History</span></a></li>
                </ul>
            </li>
    
            <li class="slide mb-2">
                <a class="side-menu__item"  data-toggle="slide" href="javascript:void">
                    <i class="fa fa-book"></i>
                    <span class="side-menu__label">Vendor Requests</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a class="slide-item"  href="{{ route('airtime-request') }}"><span>Airtime Request</span></a></li>
                    <li><a class="slide-item"  href="{{ route('data-request') }}"><span>Data Request</span></a></li>
                    <li><a class="slide-item"  href="{{ route('cabletv-request') }}"><span>Cable TV Request</span></a></li>
                    <li><a class="slide-item"  href="{{ route('electricity-request') }}"><span>Electricicty Request</span></a></li>
                    <li><a class="slide-item"  href="{{ route('education-request') }}"><span>Education Request</span></a></li>
                </ul>
            </li>
    
            <li class="slide mb-2">
                <a class="side-menu__item"  data-toggle="slide" href="javascript:void">
                    <i class="fa fa-users"></i>
                    <span class="side-menu__label">User Management </span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a class="slide-item"  href="{{ route('userlist') }}"><span>User Lists</span></a></li>
                    <li><a class="slide-item"  href="{{ route('user-mgt') }}"><span>User Management</span></a></li>
                </ul>
            </li>
    
            <li class="slide mb-2">
                <a class="side-menu__item"  data-toggle="slide" href="javascript:void">
                    <i class="fa fa-history"></i>
                    <span class="side-menu__label">Transactions</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a class="slide-item"  href="{{ route('airtimecash-admin-history') }}"><span>Airtime to Cash History</span></a></li>
                    <li><a class="slide-item"  href="{{ route('bank-withdrawals') }}"><span>Bank Withdrawal Request</span></a></li>
                    <li><a class="slide-item"  href="{{ route('admin-transactions-histories') }}"><span>All Transactions History</span></a></li>
                    <li><a class="slide-item"  href="{{ route('admin-pending-transactions-histories') }}"><span>Pending Delivery History</span></a></li>
                    <li><a class="slide-item"  href="{{ route('admin-successful-transactions-histories') }}"><span>Successful Transaction History</span></a></li>
                    <li><a class="slide-item"  href="{{ route('admin-awaiting-transactions-histories') }}"><span>Processed Delivery History</span></a></li>
                    <li><a class="slide-item"  href="{{ route('admin-modify-status') }}"><span>Modify Transaction Status</span></a></li>
                </ul>
            </li>
    
            <li class="slide mb-2">
                <a class="side-menu__item"  data-toggle="slide" href="javascript:void">
                    <i class="fa fa-server"></i>
                    <span class="side-menu__label">Server Setup</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a class="slide-item"  href="{{ route('api-index') }}"><span>API Management</span></a></li>
                    <li><a class="slide-item"  href="{{ route('api-switch') }}"><span>API Switch</span></a></li>
                    <li><a class="slide-item"  href="{{ route('app-server') }}"><span>App Server Management</span></a></li>
                </ul>
            </li>
            
        </ul>
        <div class="app-sidebar-help">
            <div class="dropdown text-center">
                <div class="help d-flex">
                    <div class="m-auto f-18">
                        <a href="{{ route('admin.sign-out') }}">
                            <strong class="d-block">
                                <i class="fa fa-sign-out"></i> {{ ucwords($adminUser->fullname) }}
                            </strong>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    