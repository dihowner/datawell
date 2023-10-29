@include('components.head')
@php
    $userMeta = $userDetail->user_meta;
    $bankInfo = isset($userMeta['bank_account']) ? json_decode($userMeta['bank_account'], true) : null;
    $bankName = isset($bankInfo['bank_name']) ? $bankInfo['bank_name'] : null;
    $accountName = isset($bankInfo['account_name']) ? $bankInfo['account_name'] : null;
    $accountNumber = isset($bankInfo['account_number']) ? $bankInfo['account_number'] : null;
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
                            <h4 class="page-title">{{ $pageTitle }}</h4>
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

                                    <form method="post" action="{{ route('user.edit-bank-account') }}">
                                        @csrf
                                        <div class="">
                                            <div class="form-group">
                                                <label for="bank_name" class="form-label">Bank Name</label>
                                                <input class="form-control" id="bank_name" name="bank_name"
                                                    value="{{ $bankName }}" placeholder="Enter bank name">
                                            </div>

                                            <div class="form-group">
                                                <label for="account_name" class="form-label">Account Name</label>
                                                <input class="form-control" id="account_name" name="account_name"
                                                    value="{{ $accountName }}" placeholder="Enter Account name">
                                            </div>

                                            <div class="form-group">
                                                <label for="account_number" class="form-label">Account Number</label>
                                                <input class="form-control" id="account_number" name="account_number"
                                                    value="{{ $accountNumber }}" placeholder="Enter Account Number">
                                            </div>

                                            <div class="form-group">
                                                @include('components.transact-pin')
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
    @include('components.footer-script')
