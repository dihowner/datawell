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
                                        <h3 class="card-title mb-0">Users List</h3>
                                    </div>
                                </div>
                                <div class="card-body p-2">

                                    @include('components.main.search')

                                    <div class="e-table">
                                        <div class="table-responsive table-lg mt-3">
                                            <table class="table table-bordered border-top text-nowrap" id="example1">
                                                <thead>
                                                    <tr>
                                                        <th class="align-top border-bottom-0 wd-5">S/N</th>
                                                        <th class="border-bottom-0">User</th>
                                                        <th class="border-bottom-0">More Info</th>
                                                        <th class="border-bottom-0">Wallets</th>
                                                        <th class="border-bottom-0">Autofunding Wallet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (count($allUsers) > 0)
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($allUsers as $user)
                                                            @php
                                                                $bankAccount = $monnifyInfo = false;
                                                                $userMeta = $user->new_user_meta;
                                                                
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
                                                                            <strong>Bank Account</strong></h6>

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
                                                                            data-id="{{ $user->id }}"
                                                                            data-profile="{{ $user }}">
                                                                            <span class="btn btn-dark">
                                                                                <i class="fa fa-paper-plane"></i>
                                                                                Generate Account
                                                                            </span>
                                                                        </a>
                                                                    @endif

                                                                </td>
                                                            </tr>
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
