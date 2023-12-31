@include('components.head')

<body class="h-100vh page-style1 light-mode default-sidebar">
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-md-7 col-lg-5">
                                <div class="card card-group mb-0">
                                    <div class="card p-4">
                                        <div class="card-body">
                                            <div class="text-center title-style mb-6">
                                                <h1 class="mb-2">Reset Password</h1>
                                                <hr>
                                            </div>

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

                                            <form method="post" action="{{ route('submit-reset-password') }}">
                                                @method('PUT')
                                                @csrf

                                                <input type="hidden" name="email" name="emailaddress"
                                                    value="{{ $emailaddress }}">
                                                <input type="hidden" name="token" name="token"
                                                    value="{{ $token }}">

                                                <div class="input-group mb-3">
                                                    <input type="email" class="form-control"
                                                        value="{{ $emailaddress }}" disabled>
                                                </div>

                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="Enter your new password">
                                                </div>

                                                <div class="input-group mb-3">
                                                    <input type="password" class="form-control" name="confirm_password"
                                                        placeholder="Confirm your new password">
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-danger btn-block"><i
                                                                class="fa fa-paper-plane"></i> Change Password</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center pt-4">
                                    <div class="font-weight-normal fs-16">Already have an account ?
                                        <a class="btn-link font-weight-normal" href="{{ url('/') }}">Login Here</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer-script');
</body>

</html>
