@include('components.head')

<body class="h-100vh page-style1 light-mode default-sidebar">
    <div class="page relative">
        <div class="page-content">
            <div class="container text-center">
                <div class="display-1 text-primary mb-5 font-weight-bold">403</div>
                <h1 class="h3  mb-3 font-weight-bold">Forbidden!</h1>
                <p class="h5 font-weight-normal mb-7 leading-normal">
                    You  may have mistyped the address or the page may have moved.
                </p>
                <a class="btn btn-primary" href="{{ route('get.login') }}"><i class="fe fe-arrow-left-circle mr-1"></i> &nbsp; Go Back</a>
            </div>
        </div>
    </div>
</body>
