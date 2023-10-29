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
                                        <h3 class="card-title mb-0">Processing Transaction Histories</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    <div class="alert alert-info">
                                        <strong class="fs-18"><i class="fa fa-bell"></i>
                                         All transaction showing here are awaiting response from the provider </strong>
                                    </div>

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

                                        <form method='post' action="{{ route('process-transaction') }}">
                                            @csrf
                                            <table
                                                class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="select_all" /> </th>
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
                                                    @if (count($userPurchases) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($userPurchases->sortByDesc('created_at') as $record)
                                                            @php
                                                                $referenceId = $record['reference'];
                                                                $userInfo = $record->user;
                                                            @endphp
                                                            <tr>
                                                                <td scope="row">
                                                                    <input type="checkbox" class=""
                                                                        name="pending_transact[]"
                                                                        value="{{ $referenceId }}" />
                                                                </td>

                                                                <td scope="row">{{ $i }}</td>

                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                        <div class="ml-3 mt-1">
                                                                            <h6 class="mb-0 font-weight-bold">
                                                                                <strong>{{ ucwords($userInfo->fullname) }}</strong>
                                                                            </h6>
                                                                            <small
                                                                                class="d-block">{{ $userInfo->username }}</small>
                                                                            <small
                                                                                class="d-block">{{ $userInfo->phone_number }}</small>
                                                                            <small
                                                                                class="d-block">{{ $userInfo->emailaddress }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <strong
                                                                        class="d-block">{{ $record['description'] }}</strong>
                                                                    <span class="d-block"><strong
                                                                            class="text-danger">Reference: </strong>
                                                                        {{ $referenceId }} </span>
                                                                    <span class="d-block"><strong
                                                                            class="text-success">Date:
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
                                                                <td>{!! $UtilityService->txStatusBySpan($record['status']) !!}</td>
                                                            </tr>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach

                                                    @endif
                                                </tbody>
                                            </table>

                                            @if (count($userPurchases) > 0)
                                                <div class="text-center mt-3 mb-3">
                                                    <button type="button" class="btn btn-success completeTransaction">
                                                        <b><i class="fa fa-check"></i> Complete</b>
                                                    </button>

                                                    <button type="button" class="btn btn-primary retryTransaction">
                                                        <b><i class="fa fa-times"></i> Retry</b>
                                                    </button>

                                                    <button type="button" class="btn btn-danger refundTransaction">
                                                        <b><i class="fa fa-times"></i> Refund</b>
                                                    </button>
                                                </div>

                                                {{ $userPurchases->links('components.custom-paginator') }}
                                            @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(".select_all").on("change", function() { //"select all" change
            let status = this.checked; // "select all" checked status
            $("input[name='pending_transact[]']").each(function(index) { //iterate all listed checkbox items
                if (index < 10) {
                    this.checked = status; //change "checkbox" checked status
                }
            });
        });

        $(".completeTransaction").on("click", function(event) {
            event.preventDefault();
            let form = $(this).parents('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to complete the process, You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Complete!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.append('<input type="hidden" name="action" value="complete">');
                    // Proceed with form submission
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request not processed', 'info');
                }
            });
        });

        $(".retryTransaction").on("click", function() {
            event.preventDefault();
            let form = $(this).parents('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to retry orders, You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Retry!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.append('<input type="hidden" name="action" value="retry">');
                    // Proceed with form submission
                    form.submit();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request not processed', 'info');
                }
            });
        });
    </script>
