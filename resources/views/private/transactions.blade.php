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
                                    <h3 class="card-title">Transaction History</h3>
                                </div>
                                <div class="card-body p-2">

                                    <div class="form-group p-3 mb-3 mt-3">
                                        <form method="GET" action="{{ route('user.search-transactions') }}">
                                            <div class="row form-group p-3">
                                                <div class="col-sm-9 col-xs-8">
                                                    <input type="text" name="query" class="form-control mb-2"
                                                        name="query" value="{{ request()->query('query') }}"
                                                        placeholder="Search for...">
                                                </div>

                                                <div class="col-sm-3 col-xs-4">
                                                    <button class="btn btn-danger btn-block" type="submit"><i
                                                            class="fa fa-search"></i>Search</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

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
                                                @if (count($userPurchases) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($userPurchases->sortByDesc('created_at') as $record)
                                                        @php
                                                            $referenceId = $record['reference'];
                                                        @endphp
                                                        <tr>
                                                            <td scope="row">{{ $i }}</td>
                                                            <td>
                                                                <strong
                                                                    class="d-block">{{ $record['description'] }}</strong>
                                                                <span class="d-block"><strong
                                                                        class="text-danger">Reference: </strong>
                                                                    {{ $record['reference'] }} </span>
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
                                                            <td>{!! $UtilityService->txStatusBySpan($record['status']) !!}</td>
                                                            <td>
                                                                <a
                                                                    href="{{ route('user-view-transaction', ['reference' => $referenceId]) }}">
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
                                        @if (count($userPurchases) > 0)
                                            {{ $userPurchases->links('components.custom-paginator') }}
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
