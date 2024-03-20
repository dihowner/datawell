@include('components.head')

@php
    $airtimeSettings = $UtilityService::airtimeInfo() !== '' ? json_decode($UtilityService::airtimeInfo(), true) : null;
    $minAirtimeAmount = $airtimeSettings !== null ? $airtimeSettings['min_value'] : 0;
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

                                                <div class="pb-3 m">

                                                </div>

                                                @if ($categoryWithImages != null)
                                                    @foreach ($categoryWithImages as $categoryItems)
                                                        <div class="col-lg-3">
                                                            <a href="{{ route('user.fetchdata', ['category' => strtolower(str_replace(array(" "), "-", $categoryItems['category_name'])) ]) }}">
                                                                <div
                                                                    class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                                    <div class="avatar avatar-lg brround d-block cover-image"
                                                                        data-image-src="{{ asset($categoryItems['image']) }}"
                                                                        style="background: url({{ asset($categoryItems['image']) }}) center center;">
                                                                    </div>
                                                                    <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                        <p
                                                                            class="mb-0 mt-1 text-dark font-weight-semibold">
                                                                            {{ str_replace('Data Bundle', '9mobile Data', $categoryItems['category_name']) }} Bundle
                                                                        </p>
                                                                        <small
                                                                            class="text-muted">{{ str_replace('Data Bundle', '9mobile Data', $categoryItems['category_name']) }}
                                                                            - Get Instant
                                                                            data recharge</small>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                {{-- <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-mtn-data') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image"
                                                                data-image-src="{{ asset('assets/images/product/mtn.jpg') }}"
                                                                style="background: url({{ asset('assets/images/product/mtn.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">
                                                                    MTN Data Bundle</p>
                                                                <small class="text-muted">MTN Data - Get Instant
                                                                    data recharge</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-airtel-data') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image"
                                                                data-image-src="{{ asset('assets/images/product/airtel.jpg') }}"
                                                                style="background: url({{ asset('assets/images/product/airtel.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">
                                                                    Airtel Data Bundle</p>
                                                                <small class="text-muted">Airtel Data - Get Instant
                                                                    data recharge</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-glo-data') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image"
                                                                data-image-src="{{ asset('assets/images/product/glo.jpg') }}"
                                                                style="background: url({{ asset('assets/images/product/glo.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">
                                                                    Glo Data Bundle</p>
                                                                <small class="text-muted">Glo Data - Get Instant
                                                                    data recharge</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-lg-3">
                                                    <a href="{{ route('user.buy-9mobile-data') }}">
                                                        <div class="d-sm-flex align-items-center border p-3 mb-3 br-7">
                                                            <div class="avatar avatar-lg brround d-block cover-image"
                                                                data-image-src="{{ asset('assets/images/product/9mobile.jpg') }}"
                                                                style="background: url({{ asset('assets/images/product/9mobile.jpg') }}) center center;">
                                                            </div>
                                                            <div class="wrapper ml-sm-3  mt-4 mt-sm-0">
                                                                <p class="mb-0 mt-1 text-dark font-weight-semibold">
                                                                    9mobile Data Bundle</p>
                                                                <small class="text-muted">9mobile Data - Get Instant
                                                                    data recharge</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div> --}}

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
