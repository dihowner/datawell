@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.main.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.main.topnav')
                    @include('components.main.breadcrumb')

                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">

                                    <div class="mb-0">
                                        <h3 class="card-title mb-0">Withdrawal History</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    @if ($errors->any() || session()->has('error'))
                                        <div class="alert alert-danger" style="font-size: 18px">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                                @if (session()->has('error'))
                                                    <li>{{ session()->get('error') }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="GET" action="{{ route('user.search-inward-txn') }}">
                                        <div class="row form-group p-3">
                                            <div class="col-sm-6 col-xs-8 mb-2">
                                                <input type="text" name="range" id="range"
                                                    class="form-control form-control-lg mb-2">
                                            </div>

                                            <div class="col-sm-2 col-xs-4 mb-2">
                                                <button class="btn btn-danger btn-lg btn-block" type="submit"><i
                                                        class="fa fa-filter"></i>Filter</button>
                                            </div>

                                            <div class="col-sm-2 col-xs-4 mb-2">
                                                <a href="{{ route('bank-withdrawals') }}" class="btn btn-dark btn-lg btn-block"><i class="fa fa-refresh"></i>
                                                    Refresh</a>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive mb-2 mt-3">
                                        <table
                                            class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>User Info</th>
                                                    <th>Description</th>
                                                    <th>Old Balance</th>
                                                    <th>Amount</th>
                                                    <th>New Balance</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($allWithdrawals) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($allWithdrawals->sortByDesc('created_at') as $record)
                                                        @php
                                                            $referenceId = $record['reference'];
                                                            $txnUser = $record->user;
                                                            $withdrawalBank = json_decode($record['bank_info'], true);
                                                            $decodeRemark = json_decode($record['remark'], true);
                                                        @endphp
                                                        <tr>
                                                            <td scope="row">{{ $i }}</td>
                                                            <td scope="row">
                                                                <h6 class="mb-0 font-weight-bold">
                                                                    <strong>{{ ucwords($txnUser->fullname) }}</strong>
                                                                </h6>
                                                                <small class="d-block">{{ $txnUser->username }}</small>
                                                                <small
                                                                    class="d-block">{{ $txnUser->phone_number }}</small>
                                                                <small
                                                                    class="d-block">{{ $txnUser->emailaddress }}</small>
                                                            </td>
                                                            <td>
                                                                <strong
                                                                    class="d-block">{{ $record['description'] }}</strong>
                                                                <span class="d-block"><strong class="text-primary">Bank
                                                                        Name:
                                                                    </strong>{{ $withdrawalBank['bank_name'] }} </span>
                                                                <span class="d-block"><strong
                                                                        class="text-primary">Account Name:
                                                                    </strong>{{ $withdrawalBank['account_name'] }}
                                                                </span>
                                                                <span class="d-block"><strong
                                                                        class="text-primary">Account Number:
                                                                    </strong>{{ $withdrawalBank['account_number'] }}
                                                                </span>
                                                                <span class="d-block"><strong class="text-danger">Date:
                                                                    </strong>
                                                                    {{ $UtilityService->niceDateFormat($record['created_at']) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $UtilityService::CURRENCY . number_format($record['old_balance'], 2) }}
                                                            </td>
                                                            <td>{{ $UtilityService::CURRENCY . number_format($record['amount'], 2) }}
                                                            </td>
                                                            <td>{{ $UtilityService::CURRENCY . number_format($record['new_balance'], 2) }}
                                                            </td>
                                                            <td>
                                                                @if ($record['status'] == '0')
                                                                    <a href="javascript:void(0)" class="approveBtn"
                                                                        data-id="{{ $record['id'] }}"
                                                                        data-amount="{{ $record['amount'] }}">
                                                                        <span class="badge badge-dark">
                                                                            <i class="fa fa-check"></i> Approve
                                                                        </span>
                                                                    </a>

                                                                    <a href="javascript:void(0)" class="declineBtn"
                                                                        data-id="{{ $record['id'] }}"
                                                                        data-amount="{{ $record['amount'] }}">
                                                                        <span class="badge badge-danger">
                                                                            <i class="fa fa-times"></i> Decline
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    {!! $UtilityService->walletStatusBySpan($record['status']) !!}
                                                                    @isset($decodeRemark['approved_by'])
                                                                        <span class="d-flex mt-2">
                                                                            <strong>Approved By: </strong>
                                                                            {{ $decodeRemark['approved_by'] }}
                                                                        </span>
                                                                    @endisset
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $i++;
                                                        @endphp
                                                    @endforeach

                                                @endif
                                            </tbody>
                                        </table>

                                        @if (count($allWithdrawals) > 0)
                                            {{ $allWithdrawals->links('components.custom-paginator') }}
                                        @endif

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".approveBtn").on("click", function() {
            let withdrawId = $(this).attr('data-id');
            let amountDeposited = $(this).attr('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve withdrawal of N" + numberWithCommas(amountDeposited) +
                    ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/bank-withdrawals/' + withdrawId + '/approve';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Withdrawal not processed', 'info');
                }
            });
        });

        $(".declineBtn").on("click", function() {
            let withdrawId = $(this).attr('data-id');
            let amountDeposited = $(this).attr('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to decline withdrawal of N" + numberWithCommas(amountDeposited) +
                    ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Decline!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/bank-withdrawals/' + withdrawId + '/decline';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Withdrawal not processed', 'info');
                }
            });
        });
    </script>
