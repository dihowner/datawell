@include('components.head')

<link href="/assets/plugins/select2/select2.min.css" rel="stylesheet" />

<body class="app sidebar-mini light-mode default-sidebar">
    @include('sweetalert::alert')

    <div class="page">
        <div class="page-main">
            <!--aside open-->

            @include('components.main.aside')

            <div class="app-content main-content">
                <div class="side-app">

                    @include('components.main.topnav')
                    @include('components.main.breadcrumb')

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">

                                    <div class="mb-0">
                                        <h3 class="card-title mb-0">Vending Gateway Switch</h3>
                                    </div>

                                </div>
                                <div class="card-body p-2">
                                    <form method="POST" action="{{ route('update-api-settings') }}">
                                        @method("PUT") @csrf
                                        <div class="row form-group m-2">

                                            @foreach ($allCategories as $categroyIndex => $category)
                                                @php
                                                    $serviceType = $category['id'] == NULL ? $category['category_name']." ".$category['parent_category'] : $category['category_name'];
                                                @endphp
                                                <div class="col-md-3 mb-4">
                                                    <label>{{ $serviceType }}</label>
                                                    <select class="form-control select2-show-search categoryApi" name="categoryApi[]">
                                                        <option value=""> -- Select API --</option>
                                                        @foreach ($allApis as $api)
                                                            <option value="{{ $api->id }}" 
                                                                {{ $category['current_api_id'] ==  $api->id ? "selected" : "" }}
                                                            >
                                                                {{ $api->api_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                            
                                        </div>

                                        <div class="col-md-12 mb-5">
                                            <button class="btn btn-danger">
                                                <i class="fa fa-paper-plane"></i> Update API
                                            </button>
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

    @include('components.footer-script')

    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>
