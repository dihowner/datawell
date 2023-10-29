<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
    <title>Login | DataWell</title>
</head>

<body>
    @include('sweetalert::alert')

    <section class="flex h-screen lg:center">
        <a href="https://datawellng.com"><img alt="The Data Well Logo" loading="lazy" width="1288" height="1288"
                decoding="async" data-nimg="1"
                class="absolute z-50 w-14 lg:w-20 xl:w-24 left-[6%] top-[15px] lg:top-[30px] shadow-md lg:shadow-2xl"
                src="frontend/images/The Data Well logo oppo.png" /></a>
        <div class="hidden lg:flex lg:flex-col lg:justify-center lg:items-center w-5/12 h-full text-white bg-[#D10A22]">
            <h4 class="text-4xl font-bold">Hello, Friend!</h4>
            <p class="w-8/12 mt-4 text-lg text-center text-gray-100">
                Don't miss out on the benefits! Join now and use our services.
            </p>
            <a href="{{ route('get.register') }}"><button
                    class="px-20 py-4 mt-8 border text-base font-bold border-white rounded-full hover:bg-white hover:text-[#D10A22] css-1eqycna"
                    tabindex="0" type="button">
                    Sign up<span class=""></span></button></a>
        </div>
        <div class="w-full flex flex-col justify-center items-center h-full container-fluid bg- lg:w-7/12">
            <div class="flex flex-col items-center py-5 shadow-lg maxWidth m-2 p-4">
                <h3 class="font-bold text-[#D10A22]">Sign in to your account</h3>

                <form action="{{ route('sign-in-account') }}"
                    class="w-full flex flex-col items-center max-w-sm mt-5 space-y-5 sm:space-y-7 md:space-y-8 lg:space-y-10 md:mt-8 lg:mt-10 col-center"
                    method="post">
                    @csrf

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

                    <div class="w-100 mx-2">
                        <label class="fw-bold fs-lg-5 my-2 text-secondary" data-shrink="false" for=":rm:"
                            id=":rm:-label">Username or Email Address</label>
                        <div class="w-100">
                            <input aria-invalid="false" id=":rm:" type="text" placeholder="Username OR Email Address"
                                class="bg-gray-100 form-control fs-5 w-100 px-2 py-2 border border-black focus:outline-none focus:border-blue-500 focus:border-b userdetail"
                                value="" name="user_detail" />
                        </div>
                    </div>
                    <div class="w-100 mx-2">
                        <label class="fw-bold fs-lg-5 my-2 text-secondary" data-shrink="false" for=":rn:"
                            id=":rn:-label">Password</label>
                        <div class="w-100">
                            <input aria-invalid="false" type="password" name="password"
                                placeholder="Enter your password"
                                class="bg-gray-100 fs-5 form-control w-100 px-2 py-2 placeholder:text-sm rounded-2xl border border-black focus:outline-none focus:border-blue-500 focus:border-b password" />
                        </div>
                    </div>
                    <a href="{{ route('forgot-password-form') }}" style="font-size: 18px"
                        class="flex text-gray-700 normal-case css-1ujsas3">
                        Forgot your password?<span class="MuiTouchRipple-root css-w0pj6f"></span></a>
                    <button
                        class="px-8 py-2 mt-8 text-sm font-bold text-white rounded-full lg:mt-12 lg:py-4 border lg:text-base lg:px-20 bg-[#D10A22] transition-all duration-500 delay-300 hover:text-[#D10A22] css-1eqycna loginMember"
                        tabindex="0" type="submit">
                        Sign in<span class=""></span>
                    </button>
                </form>
                <p class="mt-4 text-gray-700 lg:hidden" style="font-size: 18px">
                    You don't have an account? <a href="{{ route('get.register') }}"><button
                            class="normal-case text-[#D10A22] css-1ujsas3" tabindex="0" type="button">
                            Sign up<span class=""></span></button></a>
                </p>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>

    <script src="{{ asset('frontend/jquery-3.3.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <!--<script src="{{ asset('frontend/auth.js') }}"></script>-->

</body>

</html>
