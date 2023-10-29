@extends('components.head')

<body class="h-100vh light-mode default-sidebar">
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
                                                <h1 class="mb-2">Admin Login</h1>
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

                                            <form method="post" action="{{ route('sign-in-admin') }}">
                                                @csrf
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control form-control-lg"
                                                        name="username" placeholder="Admin Username">
                                                </div>
                                                <div class="input-group mb-4">
                                                    <input type="password" name="password"
                                                        class="form-control form-control-lg" placeholder="Password">
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-danger btn-block"><i
                                                                class="fe fe-log-in"></i> Login</button>
                                                    </div>
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
        </div>
    </div>

    @include('components.footer-script');

</body>

</html>
