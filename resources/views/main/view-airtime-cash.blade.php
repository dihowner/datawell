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
                        <div class="col-md-4 offset-md-4">
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="page-rightheader ml-auto d-lg-flex justify-content-between d-none">

                                        <div class="mr-5 mb-0">
                                            <h3 class="card-title mb-0">Conversion Details</h3>
                                            <span
                                                class="block">{{ $UtilityService->niceDateFormat($txnRecord['created_at']) }}</span>
                                            <div class="d-block">{!! $UtilityService->walletStatusBySpan($txnRecord['status']) !!}</div>
                                        </div>

                                        <div class="ml-5 mb-0">
                                            <a class="btn btn-dark" href="{{ route('airtimecash-admin-history') }}">
                                                <i class="fa fa-arrow-circle-left"></i> <span> Go Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-5 fs-16">

                                    <div class="row">
                                        <div class="col">
                                            <h6>Old Wallet Balance</h6>
                                            <p class="text-muted font-weight-normal">
                                                {{ $UtilityService::CURRENCY . number_format($txnRecord['old_balance'], 2) }}
                                            </p>
                                        </div>

                                        <div class="col">
                                            <h6>Sold At</h6>
                                            <p class="text-muted font-weight-normal">
                                                {{ $UtilityService::CURRENCY . number_format($txnRecord['amount'], 2) }}
                                            </p>
                                        </div>

                                        <div class="col">
                                            <h6>New Wallet Balance</h6>
                                            <p class="text-muted font-weight-normal">
                                                {{ $UtilityService::CURRENCY . number_format($txnRecord['new_balance'], 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <h6>Description</h6>
                                        <strong
                                            class="text-dark d-block">{{ $txnRecord['description'] }}</strong>
                                        <span class="d-block"><strong>Reference: </strong>
                                            {{ $txnRecord['reference'] }}</span>
                                    </div>

                                    @if($txnRecord['airtime_reciever'] != "" AND $txnRecord['airtime_reciever'] != "")
                                        <div class="form-group mb-4">
                                            <h6>Sent To</h6>
                                            <span class="d-block">{{ $txnRecord['airtime_reciever'] }}</span>
                                        </div>

                                        <div class="form-group mb-4">
                                            <h6>Sent From</h6>
                                            <span class="d-block">{{ $txnRecord['airtime_sender'] }}</span>
                                        </div>
                                    @endif

                                    @if ($txnRecord['additional_note'] != null)
                                        <div class="form-group border-top">
                                            <h6 class="mt-2">Note To Admin</h6>
                                            <p class="text-muted font-weight-normal">{{ $txnRecord['additional_note'] }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="text-center mt-2 border-top p-2">
                                        @if ($txnRecord['status'] == '0')
                                            <a href="javascript:void(0)" class="approveBtn"
                                                data-conversion="{{ $txnRecord }}">
                                                <span class="badge badge-dark">
                                                    <i class="fa fa-check"></i> Approve
                                                </span>
                                            </a>

                                            <a href="javascript:void(0)" class="declineBtn"
                                                data-conversion="{{ $txnRecord }}">
                                                <span class="badge badge-danger">
                                                    <i class="fa fa-times"></i> Decline
                                                </span>
                                            </a>
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
        $(document).on("click", ".approveBtn", function() {
            let conversionData = JSON.parse($(this).attr('data-conversion'));
            const description = conversionData.network.toUpperCase() + " " + numberWithCommas(conversionData
                .airtime_amount);

            console.log(conversionData);

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to approve " + description + " for NGN" + numberWithCommas(
                    conversionData.amount) + ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/airtime-cash/' + conversionData.id + '/approve';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request cancel', 'error');
                }
            });

        });

        $(document).on("click", ".declineBtn", function() {
            let conversionData = JSON.parse($(this).attr('data-conversion'));
            const description = conversionData.network.toUpperCase() + " " + numberWithCommas(conversionData
                .airtime_amount);

            console.log(conversionData);

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to decline " + description + ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/airtime-cash/' + conversionData.id + '/decline';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request cancel', 'error');
                }
            });

        });
    </script>
