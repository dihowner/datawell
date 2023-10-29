@include('components.head')

<link href="/assets/plugins/select2/select2.min.css" rel="stylesheet" />

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.topnav')

                    <div class="page-header">
                        <div class="page-leftheader">
                            <h4 class="page-title">{{ $pageTitle }}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">

                                <div class="card-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger" style="font-size: 18px">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @include('components.user-balance')

                                    <form method="post" action="{{ route('user.submit-upgrade-plan') }}">
                                        @method('PUT')
                                        @csrf

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Current Plan</label>
                                                <input class="form-control form-control-lg"
                                                    value="{{ $userDetail->plan->plan_name }}" readonly>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Select Plan</label>
                                                <select class="form-control select2-show-search newPlan" name="newPlan">
                                                    <option value=""> -- Select New Plan --</option>
                                                    @if ($allPlans !== false)
                                                        @foreach ($allPlans as $plan)
                                                            <option value="{{ $plan['id'] }}"
                                                                data-plan-info=" {{ $plan }}"
                                                                <?php echo $plan['id'] === $userDetail['plan']['id'] ? 'disabled' : ''; ?>>
                                                                {{ $plan['plan_name'] }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label">Amount</label>
                                                <input class="form-control form-control-lg plan-amount" value="0.00"
                                                    disabled>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                @include('components.transact-pin')
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-danger mt-4 mb-0">Submit</button>
                                    </form>
                                </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    </div>
    @include('components.footer-script')

    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).on('change', '.newPlan', async function() {
            let selectedPlan = $(".newPlan option:selected")
            planId = selectedPlan.val();

            if (planId != "" && planId != undefined) {
                planInfo = JSON.parse(selectedPlan.attr('data-plan-info'));

                $(".plan-amount").val("₦" + planInfo.amount.toLocaleString())

                Swal.fire({
                    title: "Plan Description",
                    html: planInfo.plan_description,
                    timer: 60000, // set auto-close timer to 5 seconds
                    timerProgressBar: true, // display a progress bar for the timer
                });
            }
        });
    </script>
