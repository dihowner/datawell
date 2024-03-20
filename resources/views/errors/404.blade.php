@include('components.head')

<body class="h-100vh page-style1 light-mode default-sidebar">
    <div class="page relative">
        <div class="page-content">
            <div class="container text-center">
                <img src="{{ asset('assets/images/svgs/404.svg') }}" alt="img" class="w-30 mb-6">
                <h1 class="h3  mb-3 font-weight-bold">Page Not Found</h1>
                <p class="h5 font-weight-normal mb-7 leading-normal">You may have mistyped the address or the page may have moved or deleted.</p>
                <a class="btn btn-primary" href="{{ route('get.login') }}"><i class="fe fe-arrow-left-circle mr-1"></i>Back to Home</a>
            </div>
        </div>
    </div>
</body>
