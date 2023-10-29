@include('components.head')

@php
    $airtimeSettings = $UtilityService::airtimeInfo() !== "" ? json_decode($UtilityService::airtimeInfo(), true) : NULL;
    $minAirtimeAmount = $airtimeSettings !== NULL ? $airtimeSettings['min_value'] : 0;
@endphp

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

                    <div class="row flex-lg-nowrap">
                        <div class="col-12">
                            <div class="row flex-lg-nowrap">
                                <div class="col-12 mb-3">
                                    <div class="e-panel card">
                                        <div class="card-body">
                                            
                                            <div class="row">

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-dstv') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/dstv.jpg') }}" style="background: url({{ asset('assets/images/product/dstv.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">DSTV Subscription</p>
                                                                <small class="text-muted">Subscribe on your DsTv and enjoy the best TV package</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-gotv') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/gotv.jpg') }}" style="background: url({{ asset('assets/images/product/gotv.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">GoTv Subscription</p>
                                                                <small class="text-muted">Subscribe on your GoTv and enjoy the best TV package</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-startimes') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/startimes.png') }}" style="background: url({{ asset('assets/images/product/startimes.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">Startimes Subscription</p>
                                                                <small class="text-muted">Subscribe on your Startimes and enjoy the best TV package</small>
                                                            </div>
                                                        </div>
                                                    </a>
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
        </div>
    </div>

@include('components.footer-script')