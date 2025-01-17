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
                                        <h3 class="card-title mb-0">Users Management</h3>
                                    </div>

                                    <div class="ml-auto mb-0">
                                        <a class="btn btn-dark" href="{{ route('user-export-csv') }}">
                                            <i class="fa fa-download"></i> <span> Export User</span>
                                        </a>
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

                                    <div class="e-table">
                                        <div class="table-responsive table-lg mt-3">
                                            <table class="table table-bordered border-top text-nowrap" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th class="align-top border-bottom-0 wd-5">S/N</th>
                                                        <th class="border-bottom-0">User</th>
                                                        <th class="border-bottom-0">More Info</th>
                                                        <th class="border-bottom-0">Wallets</th>
                                                        <th class="border-bottom-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($allUsers) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($allUsers as $user)
                                                            @php
                                                                $userId = $user->id;
                                                                $bankAccount = $monnifyInfo = false;
                                                                $userMeta = $user->new_user_meta;
                                                                $accessControl = json_decode($user->access_control);
                                                                
                                                                if ($userMeta !== false) {
                                                                    $monnifyInfo = isset($userMeta['monnify']) ? $userMeta['monnify'] : false;
                                                                    $bankAccount = isset($userMeta['bank_account']) ? json_decode($userMeta['bank_account']) : false;
                                                                }
                                                            @endphp

                                                            <tr>
                                                                <td class="align-middle">
                                                                    {{ $i }}
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="d-flex">
                                                                        <span class="avatar brround avatar-md d-block"
                                                                            style="background-image: url({{ asset('assets/images/users/user-default.jpg') }}); height: 50px; width: 50px"></span>
                                                                        <div class="ml-3 mt-1">
                                                                            <h6 class="mb-0 font-weight-bold">
                                                                                <strong>{{ ucwords($user->fullname) }}</strong>
                                                                            </h6>
                                                                            <small
                                                                                class="d-block">{{ $user->username }}</small>
                                                                            <small
                                                                                class="d-block">{{ $user->phone_number }}</small>
                                                                            <small
                                                                                class="d-block">{{ $user->emailaddress }}</small>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td class="align-middle">
                                                                    {{ $user->plan->plan_name }}
                                                                    <small class="d-block text-primary fs-15">
                                                                        {{ $UtilityService->niceDateFormat($user->created_at) }}
                                                                    </small>

                                                                    @if ($bankAccount !== false)
                                                                        <h6 class="mt-2  mb-0 font-weight-bold">
                                                                            <strong>Bank Account</strong>
                                                                        </h6>

                                                                        <small class="d-block">
                                                                            {{ $bankAccount->bank_name }}
                                                                        </small>
                                                                        <small class="d-block">
                                                                            {{ $bankAccount->account_name }}
                                                                        </small>
                                                                        <small class="d-block">
                                                                            {{ $bankAccount->account_number }}
                                                                        </small>
                                                                    @endif
                                                                </td>

                                                                <td class="align-middle">
                                                                    <strong
                                                                        class="font-weight-bold">{{ $UtilityService::CURRENCY . number_format($user->wallet_balance, 2) }}</strong>
                                                                    <small class="d-block text-primary fs-15"><strong>Airtime
                                                                            Cash: </strong>
                                                                        {{ $UtilityService::CURRENCY . number_format($user->airtime_cash, 2) }}</small>
                                                                </td>

                                                                <td class="align-middle">
                                                                    @if ($monnifyInfo !== false)
                                                                        @isset($monnifyInfo['Wema Bank'])
                                                                            <span class="d-block fs-16">
                                                                                Wema Bank: {{ $monnifyInfo['Wema Bank'] }}
                                                                            </span>
                                                                        @endisset

                                                                        @isset($monnifyInfo['Sterling Bank'])
                                                                            <span class="d-block fs-16">
                                                                                Sterling Bank:
                                                                                {{ $monnifyInfo['Sterling Bank'] }}
                                                                            </span>
                                                                        @endisset
                                                                    @else
                                                                        <a href="javascript:void(0)" class="generateVA"
                                                                            data-id="{{ $userId }}"
                                                                            data-profile="{{ $user }}">
                                                                            <span class="btn btn-dark btn-sm">
                                                                                <i class="fa fa-paper-plane"></i>
                                                                            </span>
                                                                        </a>
                                                                    @endif

                                                                    @if ($accessControl->suspension->status == '0')
                                                                        <a href="{{ route('update-user-access-control', ['id' => $userId, 'action' => 'suspend']) }}"
                                                                            class="btn btn-primary" onclick="return confirm('Suspend user \n\n Action is irreversible')">Suspend</a>
                                                                    @else
                                                                        <a href="{{ route('update-user-access-control', ['id' => $userId, 'action' => 'unsuspend']) }}"
                                                                            class="btn btn-info" onclick="return confirm('Unsuspend user \n\n Action is irreversible')">Unsuspend</a>
                                                                    @endif

                                                                    <a href="javacript:void(0)" data-toggle="modal"
                                                                        data-target="#editProfile{{ $userId }}">
                                                                        <span class="btn btn-danger btn-sm">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </span>
                                                                    </a>

                                                                </td>
                                                            </tr>

                                                            <div class="modal fade" id="editProfile{{ $userId }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="normalmodal" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <form method="POST"
                                                                        action="{{ route('update-user', ['id' => $userId]) }}">
                                                                        @method('PUT')
                                                                        @csrf
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="normalmodal1"><strong>Modify
                                                                                        User
                                                                                        ({{ ucwords($user->fullname) }})
                                                                                    </strong>
                                                                                </h5>
                                                                                <button type="button" class="close"
                                                                                    data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span aria-hidden="true">×</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">

                                                                                    <div class="col-md-6 form-group mb-2">
                                                                                        <label for="serviceType"
                                                                                            class="form-label">Transaction
                                                                                            PIN</label>
                                                                                        <input
                                                                                            class="form-control form-control-lg"
                                                                                            maxlength="4"
                                                                                            name="transactpin"
                                                                                            value="{{ $user->secret_pin }}"
                                                                                            placeholder="Enter secret pin">
                                                                                    </div>
    
                                                                                    <div class="col-md-6 form-group mb-2">
                                                                                        <label for="plan_id"
                                                                                            class="form-label">Plan</label>
                                                                                        <select
                                                                                            class="form-control form-control-lg"
                                                                                            name="plan_id">
                                                                                            @if (count($allPlans) > 0)
                                                                                                @foreach ($allPlans as $plan)
                                                                                                    <option
                                                                                                        value="{{ $plan->id }}"
                                                                                                        <?php echo $plan['id'] === $user->plan_id ? 'selected' : ''; ?>>
                                                                                                        {{ $plan->plan_name }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>
    
                                                                                    <div class="col-md-12 form-group mb-2">
                                                                                        <label for="vending_restriction"
                                                                                            class="form-label">Vending Restriction</label>
                                                                                        <select class="form-control" name="vending_restriction">
                                                                                            <option value="restricted" {{ $accessControl->vending->status == "restricted" ? "selected='selected'" : "" }}>Allow User to buy with limit</option>
                                                                                            <option value="offlimit" {{ $accessControl->vending->status == "offlimit" ? "selected='selected'" : "" }}> Allow User to buy without limit</option>
                                                                                        </select>
                                                                                    </div>
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

                                            @if (count($allUsers) > 0)
                                                {{ $allUsers->links('components.custom-paginator') }}
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
        $(document).on("click", ".generateVA", function() {
            let profileData = JSON.parse($(this).attr('data-profile'));

            console.log(profileData.fullname);

            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to generate virtual account for " + profileData.fullname +
                    ". You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete action here
                    window.location.href = '/main/user/' + profileData.id + '/generate-va';
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Cancelled', 'Request cancel', 'error');
                }
            });

        });
    </script>
