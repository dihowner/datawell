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
                                        <h3 class="card-title mb-0">All Plans</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-dark" href="{{ route('createplan-view') }}">
                                            <i class="fa fa-plus-circle"></i> <span> Create Plan</span>
                                        </a>
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
                                        <form method="GET" action="{{ route('search-plan') }}">
                                            <div class="row form-group p-3">
                                                <div class="col-sm-6 col-xs-8">
                                                    <input type="text" name="query"
                                                        class="form-control form-control-lg mb-2" name="query"
                                                        value="{{ request()->query('query') }}"
                                                        placeholder="Search for...">
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
                                        </form>
                                    </div>

                                    <div class="table-responsive mb-2">
                                        <table
                                            class="table table-striped table-bordered card-table table-vcenter text-nowrap mb-3">
                                            <thead>
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Plan Name</th>
                                                    <th>Upgrade Fee</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (count($allPlans) > 0)
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach ($allPlans as $planList)
                                                        @php
                                                            $planId = $planList['id'];
                                                            $planName = $planList['plan_name'];
                                                            $planAmount = $planList['amount'];
                                                            $decodeRemark = json_decode($planList['remarks'], true);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $i }}</td>
                                                            <td>
                                                                <strong class="fs-18">{{ $planName }}
                                                                    <span class="badge badge-dark">{{ $planList['product_pricing_count'] }} Products</span>
                                                                </strong>
                                                                <span class="d-block text-primary"><strong>Created By:
                                                                    </strong> {{ $decodeRemark['created_by'] }}</span>
                                                                <span class="d-block text-danger"><strong>Date Created:
                                                                    </strong>
                                                                    {{ $UtilityService->niceDateFormat($planList['created_at']) }}</span>
                                                                @isset($decodeRemark['updated_by'])
                                                                    <span class="d-block text-info"><strong>Updated By:
                                                                        </strong> {{ $decodeRemark['updated_by'] }}</span>
                                                                    <span class="d-block text-danger"><strong>Date Updated:
                                                                        </strong>
                                                                        {{ $UtilityService->niceDateFormat($planList['updated_at']) }}</span>
                                                                @endisset
                                                            </td>
                                                            <td>{{ $UtilityService::CURRENCY . number_format($planAmount, 2) }}
                                                            </td>
                                                            <td>
                                                                <a href="javacript:void(0)" data-toggle="modal"
                                                                    data-target="#editPlan{{ $planId }}">
                                                                    <span class="badge badge-primary">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </span>
                                                                </a>

                                                                <a href="{{ route('setPrice', $planId) }}">
                                                                    <span class="badge badge-info">
                                                                        <i class="fa fa-money"></i>
                                                                    </span>
                                                                </a>

                                                                @if ($UtilityService->defaultPlanId() != $planId)
                                                                    <a
                                                                        href="{{ route('delete-plan', ['id' => $planId]) }}"
                                                                        onclick="return confirm('Are you sure you want to delete {{ $planName }} plan. Deleting this will affect all users on this plan from making any purchase on this platform \n \n Proceed ?')"
                                                                    >
                                                                        <span class="badge badge-danger">
                                                                            <i class="fa fa-trash"></i>
                                                                        </span>
                                                                    </a>
                                                                @endif

                                                            </td>
                                                        </tr>

                                                        <div class="modal fade" id="editPlan{{ $planId }}"
                                                            tabindex="-1" role="dialog" aria-labelledby="normalmodal"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <form method="POST"
                                                                    action="{{ route('update-plan', ['id' => $planId]) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="normalmodal1">
                                                                                <strong>Edit Plan
                                                                                    ({{ $planName }})</strong></h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="form-group mb-2">
                                                                                <label for="serviceType"
                                                                                    class="form-label">Plan Name</label>
                                                                                <input
                                                                                    class="form-control form-control-lg"
                                                                                    name="plan_name"
                                                                                    value="{{ $planName }}"
                                                                                    placeholder="Enter plan name">
                                                                            </div>

                                                                            <div class="form-group mb-2">
                                                                                <label for="serviceType"
                                                                                    class="form-label">Upgrade
                                                                                    Fee</label>
                                                                                <input
                                                                                    class="form-control form-control-lg"
                                                                                    name="upgrade_fee"
                                                                                    value="{{ $planAmount }}"
                                                                                    placeholder="Enter upgrade plan amount">
                                                                            </div>

                                                                            <div class="form-group mb-2">
                                                                                <label for="serviceType"
                                                                                    class="form-label">Description</label>
                                                                                <textarea class="form-control form-control-lg" name="plan_description" row="5"
                                                                                    placeholder="Enter plan description">{{ $planList['plan_description'] }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Save
                                                                                changes</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        @php
                                                            $i++;
                                                        @endphp
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        @if (count($allPlans) > 0)
                                            {{ $allPlans->links('components.custom-paginator') }}
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
