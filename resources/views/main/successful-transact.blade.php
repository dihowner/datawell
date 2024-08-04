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
                                        <h3 class="card-title mb-0">Completed Transaction Histories</h3>
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
