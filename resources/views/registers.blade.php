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
                                                <h1 class="mb-2">Create Account</h1>
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

                                            <form method="post" action="{{ route('joinus') }}">
                                                @csrf

                                                <div class="input-group mb-3">
                                                    <span class="input-group-addon">
                                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg"
                                                            height="24" viewBox="0 0 24 24" width="24">
                                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                                            <path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z"
                                                                opacity=".3" />
                                                            <circle cx="12" cy="8" opacity=".3"
                                                                r="2" />
                                                            <path
                                                                d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Your full name" name="fullname">
                                                </div>

                                                <div class="input-group mb-3">
                                                    <span class="input-group-addon">
                                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg"
                                                            height="24" viewBox="0 0 24 24" width="24">
                                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                                            <path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z"
                                                                opacity=".3" />
                                                            <circle cx="12" cy="8" opacity=".3"
                                                                r="2" />
                                                            <path
                                                                d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter your desired Username" name="username">
                                                </div>

                                                <div class="input-group mb-3">
                                                    <span class="input-group-addon">
                                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg"
                                                            height="24" viewBox="0 0 24 24" width="24">
                                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                                            <path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z"
                                                                opacity=".3" />
                                                            <circle cx="12" cy="8" opacity=".3"
                                                                r="2" />
                                                            <path
                                                                d="M22 5.999H2c-.552 0-1 .449-1 1v10c0 .552.448 1 1 1h20c.552 0 1-.448 1-1V6.999c0-.551-.448-1-1-1zm0 2l-8 5-8-5v-1l8 5 8-5v1z" />
                                                        </svg>
                                                    </span>
                                                    <input type="email" class="form-control"
                                                        placeholder="Enter your valid Email Address"
                                                        name="emailaddress">
                                                </div>

                                                <div class="input-group mb-3">
                                                    <span class="input-group-addon">
                                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg"
                                                            height="24" viewBox="0 0 24 24" width="24">
                                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                                            <path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z"
                                                                opacity=".3" />
                                                            <circle cx="12" cy="8" opacity=".3"
                                                                r="2" />
                                                            <path
                                                                d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter your valid mobile number"
                                                        name="phone_number">
                                                </div>

                                                <div class="input-group mb-4">
                                                    <span class="input-group-addon">
                                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg"
                                                            height="24" viewBox="0 0 24 24" width="24">
                                                            <g fill="none">
                                                                <path d="M0 0h24v24H0V0z" />
                                                                <path d="M0 0h24v24H0V0z" opacity=".87" />
                                                            </g>
                                                            <path
                                                                d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z"
                                                                opacity=".3" />
                                                            <path
                                                                d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
                                                        </svg>
                                                    </span>
                                                    <input type="password" class="form-control"
                                                        placeholder="Password" name="password">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-danger btn-block"><i
                                                                class="fe fe-user-plus"></i> Sign Up</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="col-12">
                                                <a href="{{ route('forgot-password-form') }}"
                                                    class="btn btn-link box-shadow-0 px-0">Forgot password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center pt-4">
                                    <div class="font-weight-normal fs-16">Already have an account ?
                                        <a class="btn-link font-weight-normal" href="{{ url('/') }}">Login
                                            Here</a>
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
