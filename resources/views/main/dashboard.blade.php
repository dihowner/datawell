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

                    {{-- @if (session()->has('info')) --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info p-3">
                                {{-- {{ $totalPendingPayment }} --}}
                                {{-- {{ session('info') }} --}}
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}

                    <div class="app-dashboard"></div>

                    <div class="row"></div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script')

    <script>
        const dashboardResponse = $(".app-dashboard");
        const statisticEndpoint = "{{ config('app.url') }}main/dashboard-counter";
        $(document).ready(function() {

            try {

                $.ajax({
                    type: 'GET',
                    url: statisticEndpoint,
                    beforeSend: function() {
                        dashboardResponse.html(`<div class='col-md-12'>
                                <span class='fs-20'>
                                    <i class='fa fa-spinner fa-spin'></i> fetching statistics
                                </span>
                            </div>`);
                    },
                    success: function(response) {
                        if (response) {
                            statisticDisplay(response, dashboardResponse)
                        } else {
                            let errorMsg = `<div class='col-md-12'>
                                                <div class='alert alert-danger'>
                                                    <span class='fs-20'>
                                                        <strong class=''>Error: </strong> Unable to fetch data, kindly refresh your page
                                                    </span>
                                                </div>
                                            </div>`
                            dashboardResponse.html(errorMsg);
                        }
                    }
                })
            } catch (error) {
                let errorMsg = `<div class='col-md-12'>
                                    <div class='alert alert-danger'>
                                        <span class='fs-20'>
                                            <strong class=''>Error: </strong> Unable to fetch data, kindly refresh your page
                                        </span>
                                    </div>
                                </div>`
                dashboardResponse.html(errorMsg);
            }
        });

        function statisticDisplay(response, outputField) {
            const CURRENCY = "&#8358;"
            console.log(response);
            resultHtml = `<div class='row'>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Total User</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.users.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Member's Wallet Balance</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.wallet.user.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Total Products</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.products.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Total Plans</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.plan.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Wallet Funding (This Month)</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.wallet.this_month.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Wallet Funding (Last Month)</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.wallet.last_month.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Sales</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.today_total.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Sales</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.yesterday_total.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.today_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.yesterday_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Week Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.this_week_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Week Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.last_week_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Month Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.this_month_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Month Sales Profit</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${CURRENCY+response.transactions.last_month_profit.toLocaleString()}</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>`;

            outputField.html(resultHtml);
        }
    </script>
