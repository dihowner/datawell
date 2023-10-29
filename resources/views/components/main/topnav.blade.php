
<div class="app-header header top-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="header-brand" href="{{ route('user.index') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo" alt="Datawell">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img dark-logo" alt="Datawell">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img mobile-logo" alt="Datawell">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img darkmobile-logo" alt="Datawell">
            </a>
            <div class="dropdown side-nav">
                <div class="app-sidebar__toggle" data-toggle="sidebar">
                    <a class="open-toggle" href="#">
                        <svg class="header-icon mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    </a>
                    <a class="close-toggle" href="#">
                        <svg class="header-icon mt-1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
                    </a>
                </div>
            </div>

            <div class="d-flex order-lg-2 ml-auto">

                <div class="dropdown profile-dropdown">
                    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                        <span>
                            <img src="{{ asset('assets/images/users/user-avatar.png') }}" alt="img" class="avatar avatar-md brround">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                        <div class="text-center">
                            <span class="dropdown-item text-center user pb-0 font-weight-bold">Admin Test</span>
                            <div class="dropdown-divider"></div>
                        </div>
                        <a class="dropdown-item d-flex" href="{{ route('admin.sign-out') }}">
                            <div class="mt-1">Sign Out</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
