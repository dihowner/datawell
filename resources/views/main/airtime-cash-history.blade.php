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
                                        <h3 class="card-title mb-0">Airtime Conversion History</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    @include('components.main.search')

                                    <div class="e-table">
                                        <div class="table-responsive table-lg mt-3">
                                            <table class="table table-bordered border-top text-nowrap" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th class="align-top border-bottom-0 wd-5">S/N</th>
                                                        <th class="border-bottom-0">User</th>
                                                        <th class="border-bottom-0">Description</th>
                                                        <th class="border-bottom-0">Old Balance</th>
                                                        <th class="border-bottom-0">Amount</th>
                                                        <th class="border-bottom-0">New Balance</th>
                                                        <th class="border-bottom-0">Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($userConversions) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($userConversions as $conversion)
                                                            @php
                                                                $description = $conversion['description'];
                                                                $referenceId = $conversion['reference'];
                                                            @endphp

                                                            <tr>
                                                                <td scope="row">{{ $i }}</td>
                                                                <td scope="row">
                                                                    <strong>{{ ucwords($conversion->user->fullname) }}</strong>
                                                                    <small
                                                                        class="d-block text-primary">{{ ucwords($conversion->user->username) }}</small>
                                                                    <small
                                                                        class="d-block text-primary">{{ strtolower($conversion->user->emailaddress) }}</small>
                                                                    <small
                                                                        class="d-block text-primary">{{ strtolower($conversion->user->phone_number) }}</small>
                                                                </td>
                                                                <td>
                                                                    <strong class="d-block">{{ $description }}</strong>
                                                                    <span class="d-block text-primary">Sent From
                                                                        {{ $conversion['airtime_sender'] }} to
                                                                        {{ $conversion['airtime_reciever'] }} </span>
                                                                    <span class="d-block"><strong
                                                                            class="text-danger">Reference: </strong>
                                                                        {{ $referenceId }} </span>
                                                                    <span class="d-block"><strong
                                                                            class="text-success">Date: </strong>
                                                                        {{ $UtilityService->niceDateFormat($conversion['created_at']) }}
                                                                    </span>
                                                                </td>
                                                                <td>{{ $UtilityService::CURRENCY . number_format($conversion['old_balance'], 2) }}
                                                                </td>
                                                                <td>{{ $UtilityService::CURRENCY . number_format($conversion['amount'], 2) }}
                                                                </td>
                                                                <td>{{ $UtilityService::CURRENCY . number_format($conversion['new_balance'], 2) }}
                                                                </td>
                                                                <td>
                                                                    @if ($conversion['status'] == '0')
                                                                        <a href="javascript:void(0)" class="approveBtn"
                                                                            data-conversion="{{ $conversion }}">
                                                                            <span class="badge badge-dark">
                                                                                <i class="fa fa-check"></i> Approve
                                                                            </span>
                                                                        </a>

                                                                        <a href="javascript:void(0)" class="declineBtn"
                                                                            data-conversion="{{ $conversion }}">
                                                                            <span class="badge badge-danger">
                                                                                <i class="fa fa-times"></i> Decline
                                                                            </span>
                                                                        </a>
                                                                    @else
                                                                        {!! $UtilityService->walletStatusBySpan($conversion['status']) !!}
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a
                                                                        href="{{ route('view-airtime-conv', ['reference' => $referenceId]) }}">
                                                                        <span class="badge badge-default">
                                                                            <i class="fa fa-eye"></i>
                                                                        </span>
                                                                    </a>
                                                                </td>

                                                            </tr>

                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>

                                            @if (count($userConversions) > 0)
                                                {{ $userConversions->links('components.custom-paginator') }}
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
