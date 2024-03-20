@include('components.head')

<body class="h-100vh page-style1 light-mode default-sidebar">
    <div class="page relative">
        <div class="page-content">
            <div class="container text-center">
                <div class="display-1 text-primary mb-5 font-weight-bold">419</div>
                <h1 class="h3  mb-3 font-weight-bold">Bad Request!</h1>
                <p class="h5 font-weight-normal mb-7 leading-normal">
                    You are attempting to access this resource after an extended period or through an unauthorized method. Please initiate the process again.
                </p>
                <a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fe fe-arrow-left-circle mr-1"></i> &nbsp; Go Back</a>
            </div>
        </div>
    </div>
</body>
