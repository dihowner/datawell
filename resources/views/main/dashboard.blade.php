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

                    @if (session()->has('info'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info p-3">
                                    {{ session('info') }}
                                </div>
                            </div>
                        </div>
                    @endif

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
                        </div>
                        <div class='row'>

                            <div class="col-lg-12 m-3">
                                <h4><strong style="font-size: 24px">MTN Statistics</strong></h4>
                            </div>
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.mtn_today_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.mtn_yesterday_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Week Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.mtn_this_week} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.mtn_this_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.mtn_last_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="col-lg-12 m-3">
                                <h4><strong style="font-size: 24px">Airtel Statistics</strong></h4>
                            </div>
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.airtel_today_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.airtel_yesterday_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Week Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.airtel_this_week} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.airtel_this_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.airtel_last_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="col-lg-12 m-3">
                                <h4><strong style="font-size: 24px">Glo Statistics</strong></h4>
                            </div>
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.glo_today_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.glo_yesterday_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Week Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.glo_this_week} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.glo_this_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.glo_last_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="col-lg-12 m-3">
                                <h4><strong style="font-size: 24px">9mobile Statistics</strong></h4>
                            </div>
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Today's Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.eti_today_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Yesterday Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.eti_yesterday_total} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Week Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.eti_this_week} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>This Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.eti_this_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <div class="mb-2 fs-18 text-muted">
                                                    <strong>Last Month Volume</strong>
                                                </div>
                                                <h1 class="font-weight-bold mb-1">${response.data.eti_last_month} GB</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            outputField.html(resultHtml);
        }
    </script>
