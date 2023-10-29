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

                                    <form method="post" action="{{ route('user.submit-share-wallet') }}">
                                        @csrf

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Receiver Details:</label>
                                                <input class="form-control form-control-lg userPhone"
                                                    name="userNamePhone"
                                                    placeholder="Enter user phone number or username" type="text">
                                                <small class="d-block text-right text-success"></small>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label for="amount" class="form-label">Enter Amount:</label>
                                                <input class="form-control form-control-lg" name="amount"
                                                    placeholder="Enter amount" type="number" min="50"
                                                    max="10000"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                @include('components.transact-pin')
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 fs-20">
                                                <strong class="text-danger">Please Note; </strong>
                                                Maximum fund transfer per transaction is <strong>&#8358;10,000</strong>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-danger mt-4 mb-0">Submit</button>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
    <script>
        $(document).on('focusout', '.userPhone', async function() {
            const userPhoneField = $('.userPhone');
            const userPhone = $('.userPhone').val();

            if (userPhone) {
                try {
                    const searchURL = "{{ config('app.url') }}search-user/";
                    $.ajax({
                        type: 'GET',
                        url: searchURL + `${userPhone}`,
                        beforeSend: function() {
                            userPhoneField.siblings('small').html(
                                "<i class='fa fa-spinner fa-spin'></i> fetching");
                        },
                        success: function(response) {
                            if (response) {
                                userPhoneField.siblings('small').html(
                                    "<i class='fa fa-check-circle'></i> " + response.fullname);
                            } else {
                                userPhoneField.siblings('small').html("");
                                Swal.fire({
                                    icon: "error",
                                    title: "Error !",
                                    html: "User does not exists"
                                });
                            }
                        }
                    })
                } catch (error) {
                    Swal.fire({
                        icon: "error",
                        title: "Error !",
                        html: error
                    });
                }
            }
        });
    </script>
