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

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-ibedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/ibedc.png') }}" 
                                                                style="background: url({{ asset('assets/images/product/ibedc.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">IBEDC Distribution</p>
                                                                <small class="text-muted">Ibadan Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-phedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/phedc.png') }}" 
                                                                style="background: url({{ asset('assets/images/product/phedc.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">PHEDC Distribution</p>
                                                                <small class="text-muted">Portharcourt Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-aedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/aedc.png') }}" 
                                                                style="background: url({{ asset('assets/images/product/aedc.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">AEDC Distribution</p>
                                                                <small class="text-muted">Abuja Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-kedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/kedco.jpg') }}" 
                                                                style="background: url({{ asset('assets/images/product/kedco.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">KEDCO Distribution</p>
                                                                <small class="text-muted">Kano Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-kaedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/kaedc.png') }}" 
                                                                style="background: url({{ asset('assets/images/product/kaedc.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">KAEDCO Distribution</p>
                                                                <small class="text-muted">Kaduna Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-eedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/eedc.png') }}" 
                                                                style="background: url({{ asset('assets/images/product/eedc.png') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">EEDC Distribution</p>
                                                                <small class="text-muted">Enugu Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-ekedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/ekedc.jpg') }}" 
                                                                style="background: url({{ asset('assets/images/product/ekedc.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">EKEDC Distribution</p>
                                                                <small class="text-muted">Eko Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-jedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/jedc.jpg') }}" 
                                                                style="background: url({{ asset('assets/images/product/jedc.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">JEDC Distribution</p>
                                                                <small class="text-muted">Jos Prepaid and Postpaid Bill Payment</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3 mb-3">
                                                    <a href="{{ route('user.buy-ikedc-bills') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar electricity-lg brround d-block cover-image" data-image-src="{{ asset('assets/images/product/ikedc.jpg') }}" 
                                                                style="background: url({{ asset('assets/images/product/ikedc.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">IKEDC Distribution</p>
                                                                <small class="text-muted">Ikeja Prepaid and Postpaid Bill Payment</small>
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