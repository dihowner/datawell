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
                            <h4 class="page-title">Personal Information</h4>
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

                                    <div class="col-sm-12 mb-5">
                                        <div class="d-block border-bottom p-2 mb-2 fs-20">
                                            <strong>Full Name : </strong> {{ $userDetail->fullname }}
                                        </div>

                                        <div class="d-block border-bottom p-2 mb-2 fs-20">
                                            <strong>User Name : </strong> {{ $userDetail->username }}
                                        </div>

                                        <div class="d-block border-bottom p-2 mb-2 fs-20">
                                            <strong>Phone Number : </strong> {{ $userDetail->phone_number }}
                                        </div>

                                        <div class="d-block border-bottom p-2 mb-2 fs-20">
                                            <strong>Email Address : </strong> {{ $userDetail->reform_email }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-5">
                                                <strong class="h1">Change Password</strong>
                                            </div>

                                            <form method="post" action="{{ route('user.edit-user-password') }}">
                                                @csrf
                                                <div class="">
                                                    <div class="form-group">
                                                        <label for="current_password" class="form-label">Current
                                                            Password</label>
                                                        <input class="form-control" type="password"
                                                            id="current_password" name="current_password"
                                                            placeholder="Enter current password">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="new_password" class="form-label">New
                                                            Password</label>
                                                        <input class="form-control" type="password" id="new_password"
                                                            name="new_password" placeholder="Enter new password">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="verify_password" class="form-label">Verify
                                                            Password</label>
                                                        <input class="form-control" type="password" id="verify_password"
                                                            name="verify_password" placeholder="Verify new password">
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-danger mt-4 mb-0">Submit</button>
                                            </form>
                                        </div>

                                        <div class="col">

                                            <div class="mb-5">
                                                <strong class="h1">Transaction PIN</strong>
                                            </div>

                                            @if ($userDetail->secret_pin === '0000')
                                                <div class="alert alert-info fs-20">
                                                    <i class="fa fa-bell-o mr-2" aria-hidden="true"></i> Kindly change
                                                    your default transaction pin. Your default transaction pin is
                                                    <strong> {{ $userDetail->secret_pin }}</strong>
                                                </div>
                                            @endif

                                            <form method="post" action="{{ route('user.modify-txn-pin') }}">
                                                @csrf
                                                <div class="">
                                                    <div class="form-group">
                                                        <label for="current_pin" class="form-label">Current Pin</label>
                                                        <input type="password" maxlength="4" class="form-control"
                                                            id="current_pin" name="current_pin"
                                                            placeholder="Enter current pin">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="new_pin" class="form-label">New Pin</label>
                                                        <input type="password" maxlength="4" class="form-control"
                                                            id="new_pin" name="new_pin"
                                                            placeholder="New transaction pin">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="verify_new_pin" class="form-label">Verify
                                                            Pin</label>
                                                        <input type="password" maxlength="4" class="form-control"
                                                            id="verify_new_pin" name="verify_new_pin"
                                                            placeholder="Verify pin">
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
        </div>
        @include('components.footer-script')
