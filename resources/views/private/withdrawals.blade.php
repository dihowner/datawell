@include('components.head')

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    {{-- <div class="page-header">
                        <div class="page-leftheader">
                            <h4 class="page-title">{{ $pageTitle }}</h4>
                        </div>
                    </div> --}}

                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Withdrawal History</h3>
                                </div>
                                <div class="card-body p-2">

                                    <form method="GET" action="{{ route('user.search-inward-txn') }}">
                                        <div class="row form-group p-3">
                                            <div class="col-sm-6 col-xs-8 mb-2">
                                                <input type="text" name="range" id="range"
                                                    class="form-control form-control-lg">
                                            </div>

                                            <div class="col-sm-2 col-xs-4 mb-2">
                                                <button class="btn btn-danger btn-block btn-lg" type="submit"><i
                                                        class="fa fa-filter"></i>Filter</button>
                                            </div>

                                            <div class="col-sm-2 col-xs-4 mb-2">
                                                <a href="{{ route('user.withdrawals-history') }}"
                                                    class="btn btn-dark btn-lg btn-block"><i class="fa fa-refresh"></i>
                                                    Refresh</a>
                                            </div>
                                        </div>
                                    </form>

                                    @if (isset($dateRange))
                                        <div class="row mb-2 m-2">
                                            <div class="col-md-12">
                                                <strong>Searching From:
                                                </strong>{{ $UtilityService->niceDateFormat($dateRange[0]) . ' - ' . $UtilityService->niceDateFormat($dateRange[1]) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="table-responsive mb-2">
                                        <table
                                            class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Description</th>
                                                    <th>Old Balance</th>
                                                    <th>Amount</th>
                                                    <th>New Balance</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($userWithdrawal) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($userWithdrawal->sortByDesc('created_at') as $record)
                                                        @php
                                                            $referenceId = $record['reference'];
                                                        @endphp
                                                        <tr>
                                                            <td scope="row">{{ $i }}</td>
                                                            <td>
                                                                <strong
                                                                    class="d-block">{{ $record['description'] }}</strong>
                                                                <span class="d-block"><strong class="text-success">Date:
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
                                                            <td>{!! $UtilityService->withdrawalStatusBySpan($record['status']) !!}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('view-withdrawal', ['reference' => $referenceId]) }}">
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
                                        @if (count($userWithdrawal) > 0)
                                            {{ $userWithdrawal->links('components.custom-paginator') }}
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
