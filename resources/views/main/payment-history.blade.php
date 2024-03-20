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
                                        <h3 class="card-title mb-0">Payment History</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    @include('components.main.search')

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
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($histories) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($histories as $history)
                                                        @php
                                                            $decodeRemark = json_decode($history['remark'], true);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>
                                                                <strong>{{ ucwords($history->user->fullname) }}</strong>
                                                                <span class="d-block text-info"><strong>Email: </strong>
                                                                    {{ ucwords($history->user->emailaddress) }}</span>
                                                                <span class="d-block text-primary"><strong>Phone:
                                                                    </strong>
                                                                    {{ ucwords($history->user->phone_number) }}</span>
                                                            </td>
                                                            <td>
                                                                {{ ucwords($history->description) }}
                                                                <span class="d-block text-danger">Channel:
                                                                    {{ ucwords($history->channel) }}</span>
                                                            </td>
                                                            <td>
                                                                {{ $UtilityService::CURRENCY . number_format($history->old_balance, 2) }}
                                                            </td>
                                                            <td>
                                                                {{ $UtilityService::CURRENCY . number_format($history->amount, 2) }}
                                                            </td>
                                                            <td>
                                                                {{ $UtilityService::CURRENCY . number_format($history->new_balance, 2) }}
                                                            </td>
                                                            <td>
                                                                {{ $UtilityService->niceDateFormat($history->created_at) }}
                                                            </td>
                                                            <td>
                                                                @if ($history->status == '0')
                                                                    <a href="javascript:void(0)" class="approveBtn"
                                                                        data-id="{{ $history->id }}"
                                                                        data-amount="{{ $history->amount }}">
                                                                        <span class="badge badge-dark">
                                                                            <i class="fa fa-check"></i> Approve
                                                                        </span>
                                                                    </a>

                                                                    <a href="javascript:void(0)" class="declineBtn"
                                                                        data-id="{{ $history->id }}"
                                                                        data-amount="{{ $history->amount }}">
                                                                        <span class="badge badge-danger">
                                                                            <i class="fa fa-times"></i> Decline
                                                                        </span>
                                                                    </a>
                                                                @else
                                                                    {!! $UtilityService->walletStatusBySpan($history->status) !!}

                                                                    @isset($decodeRemark['approved_by'])
                                                                        <span class="d-flex mt-2">
                                                                            <strong class="">Approved By: </strong>
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

                                        @if (count($histories) > 0)
                                            {{ $histories->links('components.custom-paginator') }}
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
            let paymentId = $(this).attr('data-id');
            let amountDeposited = $(this).attr('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to approve N" + numberWithCommas(amountDeposited) +
                    ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/payment/' + paymentId + '/approve';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Payment not processed', 'info');
                }
            });
        });

        $(".declineBtn").on("click", function() {
            let paymentId = $(this).attr('data-id');
            let amountDeposited = $(this).attr('data-amount');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to decline N" + numberWithCommas(amountDeposited) +
                    ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Decline!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/payment/' + paymentId + '/decline';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Payment not processed', 'info');
                }
            });
        });
    </script>
