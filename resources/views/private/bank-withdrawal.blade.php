@include('components.head')

@php
    $bankInfo = json_decode($userDetail['user_meta']['bank_account'], true);
    $bankName = $bankInfo['bank_name'];
    $accountName = $bankInfo['account_name'];
    $accountNumber = $bankInfo['account_number'];
    
    echo $bankName;
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

                                    <form method="post" action="{{ route('user.submit-withdrawal-request') }}">
                                        @csrf

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Available Balance:</label>
                                                <input class="form-control form-control-lg userPhone"
                                                    name="userNamePhone"
                                                    value="{{ $UtilityService::CURRENCY . number_format($userDetail->airtime_cash, 2) }}"
                                                    disabled>
                                                <small class="d-block text-right text-success"></small>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Enter Amount:</label>
                                                <input class="form-control form-control-lg" name="amount"
                                                    placeholder="Enter amount" type="number" min="50"
                                                    max="10000"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Bank Account </label>
                                                <input class="form-control form-control-lg" type="text"
                                                    value="{{ $bankName }}" disabled>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Account Name</label>
                                                <input class="form-control form-control-lg" type="text"
                                                    value="{{ $accountName }}" disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Account Number</label>
                                                <input class="form-control form-control-lg" type="text"
                                                    value="{{ $accountNumber }}" disabled>
                                            </div>

                                            <div class="col-sm-6 mb-3">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
