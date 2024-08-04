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
                                        <h3 class="card-title mb-0">Modify Status</h3>
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
                                            
                                    <div class="form-group p-3 mb-3 mt-3">
                                        <form method="GET" action="{{ route('admin-search-order-modify') }}">
                                            <div class="row form-group p-3">
                                                <div class="col-sm-4 col-xs-8">
                                                    <input type="text" name="query"
                                                    class="form-control form-control-lg mb-2" name="query"
                                                    value="{{ request()->query('query') }}"
                                                    placeholder="Search for...">
                                            </div>

                                            <div class="col-sm-2 col-xs-8">
                                                <select class="form-control form-control-lg mb-2" name="status">
                                                    <option value="all" {{ request()->query('status') == 'all' ? "selected" : "" }}>All Transaction</option>
                                                    <option value="0" {{ request()->query('status') == '0' ? "selected" : "" }}>Pending Transaction</option>
                                                    <option value="1" {{ request()->query('status') == '1' ? "selected" : "" }}>Successful Transaction</option>
                                                    <option value="2" {{ request()->query('status') == '2' ? "selected" : "" }}>Awaiting Transaction</option>
                                                    {{-- <option value="3" {{ request()->query('status') == '3' ? "selected" : "" }}>Refunded Transaction</option> --}}
                                                </select>
                                            </div>
                                            
                                            <div class="col-sm-2 col-xs-4">
                                                <button class="btn btn-danger btn-lg btn-block" type="submit"><i
                                                    class="fa fa-search"></i>Search</button>
                                                </div>
                                                
                                                <div class="col-sm-2 col-xs-4">
                                                    <a href="{{ route('planlist') }}" class="btn btn-dark btn-lg btn-block"><i
                                                        class="fa fa-refresh"></i> Reset</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                        
                                    <form action="{{ route('process-transaction') }}" method="POST">
                                        <div class="table-responsive mb-2 mt-3">
                                            @csrf
                                            <table class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
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
                                                    @if ($userPurchases !== null)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($userPurchases as $record)
                                                            @php
                                                                $referenceId = $record['reference'];
                                                                $userInfo = $record->user;
                                                            @endphp
                                                            <tr>
                                                                <td scope="row">
                                                                    <input type="checkbox" name="reference[]" value="{{ $referenceId }}" />
                                                                </td>

                                                                <td scope="row">{{ $i }}</td>
                                                                
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                        <div class="ml-3 mt-1">
                                                                            <h6 class="mb-0 font-weight-bold"><strong>{{ ucwords($userInfo->fullname) }}</strong></h6>
                                                                            <small class="d-block">{{ $userInfo->username }}</small>
                                                                            <small class="d-block">{{ $userInfo->phone_number }}</small>
                                                                            <small class="d-block">{{ $userInfo->emailaddress }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <strong class="d-block">{{ $record['description'] }}</strong>
                                                                    <span class="d-block"><strong class="text-danger">Reference: </strong> {{ $record['reference'] }} </span>
                                                                    <span class="d-block"><strong class="text-success">Date: </strong> {{ $UtilityService->niceDateFormat($record['created_at']) }} </span>
                                                                </td>
                                                                <td>{{ $UtilityService::CURRENCY.number_format($record['old_balance'], 2) }}</td>
                                                                <td>{{ $UtilityService::CURRENCY.number_format($record['amount'], 2) }}</td>
                                                                <td>{{ $UtilityService::CURRENCY.number_format($record['new_balance'], 2) }}</td>
                                                                <td>{!! $UtilityService->txStatusBySpan($record['status']) !!}</td>
                                                            </tr>
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach

                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row m-3">
                                            <div class="col-auto">
                                                <select class="form-control" name="action" required="">
                                                    <option value="retry">Pending Order</option>
                                                    <option value="complete">Completed Order</option>
                                                    <option value="awaiting">Awaiting response</option>
                                                    <option value="refund">Refund Order</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button class="btn btn-primary" type="submit"><b>Submit</b></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script')

    <script>
        $(".select_all").on("change", function() { //"select all" change
            let status = this.checked; // "select all" checked status
            $("input[name='reference[]']").each(function(index) { //iterate all listed checkbox items
                if (index < 10) {
                    this.checked = status; //change "checkbox" checked status
                }
            });
        });
    </script>