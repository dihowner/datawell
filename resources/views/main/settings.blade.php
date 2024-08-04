@include('components.head')

@php
    $monnifySettings = json_decode($UtilityService->monnifyInfo(), true);
    $bankSettings = $UtilityService->bankInformation();
    $bankCharges = json_decode($bankSettings['bank_charges'], true);
    
    $flutterwaveInfo = json_decode($UtilityService->flutterwaveInfo(), true);
    $paystackInfo = json_decode($UtilityService->paystackInfo(), true);
    $airtimeInfo = json_decode($UtilityService->airtimeInfo(), true);
    $airtimeConversion = json_decode($UtilityService->airtimeConversion(), true);
    $vendingRestriction = json_decode($UtilityService->vendingRestriction(), true);
    
    $mtnConversion = $airtimeConversion['mtn'];
    $airtelConversion = $airtimeConversion['airtel'];
    $gloConversion = $airtimeConversion['glo'];
    $etiConversion = $airtimeConversion['9mobile'];
    $conversionSettingStatus = $airtimeConversion['settings']['status'];
@endphp

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

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Vending Restriction</h3>
                                </div>
                                <form method="post" action="{{ route('update-restrict-vending') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">
                                        <div class="row"> 

                                            <div class="col-md-12 mb-2">
                                                <label>Enter Amount </label>
                                                <input class="form-control form-control-lg" value="{{ $vendingRestriction['unverified_purchase'] }}"
                                                    placeholder="Amount user can purchase if admin restrict them" name="unverified_purchase"/>
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Status</label>
                                                <select class="form-control form-control-lg" name="status">
                                                    <option value="enable" {{ $vendingRestriction['status'] == 'enable' ? "selected='selected'" : ""}}>
                                                        Enable Purchase Restriction
                                                    </option>
                                                    <option value="disable" {{ $vendingRestriction['status'] == 'disable' ? "selected='selected'" : ""}}>
                                                        Disable Purchase Restriction
                                                    </option>
                                                </select>
                                                <input class="form-control form-control-lg" type="hidden"
                                                    name="updateVendRestriction" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Monnify Settings</h3>
                                </div>
                                <form method="post" action="{{ route('update-monnify') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">

                                        <div class="form-group mb-2">
                                            <label>Base URL</label>
                                            <input class="form-control form-control-lg" placeholder="Enter base url"
                                                value="{{ $monnifySettings['baseUrl'] }}" disabled />
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6 mb-2">
                                                <label>API Key</label>
                                                <input class="form-control form-control-lg" placeholder="Enter Api key"
                                                    name="apiKey" value="{{ $monnifySettings['apiKey'] }}" />
                                                <input type="hidden" class="form-control form-control-lg"
                                                    name="updateMonnify" value="updateMonnify" />
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Secret Key</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter secret key" name="secKey"
                                                    value="{{ $monnifySettings['secKey'] }}" />
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Contract Code</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter contract code" name="contractCode"
                                                    value="{{ $monnifySettings['contractCode'] }}" />
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Charges Type</label>
                                                <select class="form-control form-control-lg" name="chargestype">
                                                    <option value="percentage"
                                                        {{ $monnifySettings['chargestype'] == 'percentage' ? "selected='selected'" : '' }}>
                                                        Percentage
                                                    </option>

                                                    <option value="flat_rate"
                                                        {{ $monnifySettings['chargestype'] == 'flat_rate' ? "selected='selected'" : '' }}>
                                                        Flat Rate
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Charges</label>
                                                <input class="form-control form-control-lg" placeholder="Enter charges"
                                                    name="charges" value="{{ $monnifySettings['charges'] }}" />
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Percentage</label>
                                                <input class="form-control form-control-lg" placeholder="Enter percent"
                                                    name="percent" value="{{ $monnifySettings['percent'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Deposit Amount</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter deposit amount" name="deposit_amount"
                                                    value="{{ $monnifySettings['deposit_amount'] }}" />
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Bank Settings</h3>
                                </div>
                                <form method="post" action="{{ route('update-bank-settings-charges') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-12 mb-2">
                                                <label>Bank Account</label>
                                                <textarea class="form-control form-control-lg" placeholder="Enter banking information" name="account_information"
                                                    rows="5">{{ $bankSettings['account_information'] }}</textarea>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Minimum Funding Amount (All Member)</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter minimum wallet" name="min_wallet"
                                                    value="{{ $bankCharges['min_wallet'] }}" />
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Stamp Duty Charges starts at</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter minimum stamp duty amount" name="min_stamp"
                                                    value="{{ $bankCharges['min_stamp'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Stamp Duty Charges</label>
                                                <input class="form-control form-control-lg" placeholder="Enter Api key"
                                                    name="stamp_duty_charge"
                                                    value="{{ $bankCharges['stamp_duty_charge'] }}" />
                                                <input type="hidden" class="form-control form-control-lg"
                                                    name="updateBank" value="updateBank" />
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Flutterwave Settings</h3>
                                </div>
                                <form method="post" action="{{ route('update-flutterwave') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-12 mb-2">
                                                <label>Public Key</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter public key" name="public_key"
                                                    value="{{ $flutterwaveInfo['public_key'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Secret Key</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter secret key" name="secret_key"
                                                    value="{{ $flutterwaveInfo['secret_key'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Charges</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter Charges" name="charges"
                                                    value="{{ $flutterwaveInfo['charges'] }}" />
                                                <input type="hidden" class="form-control form-control-lg"
                                                    name="updateFlutterwave" value="updateFlutterwave" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Charges Type</label>
                                                <select class="form-control form-control-lg" name="chargestype">
                                                    <option value="percentage"
                                                        {{ $flutterwaveInfo['chargesType'] == 'percentage' ? "selected='selected'" : '' }}>
                                                        Percentage
                                                    </option>

                                                    <option value="flat_rate"
                                                        {{ $flutterwaveInfo['chargesType'] == 'flat_rate' ? "selected='selected'" : '' }}>
                                                        Flat Rate
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="custom-switch">
                                                    <span class="custom-switch-description mr-2 fs-18">Allow
                                                        Flutterwave</span>
                                                    <input type="checkbox" name="status" class="custom-switch-input"
                                                        {{ $flutterwaveInfo['status'] == 'active' ? 'checked' : '' }}>
                                                    <span
                                                        class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Paystack Settings</h3>
                                </div>
                                <form method="post" action="{{ route('update-paystack') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-12 mb-2">
                                                <label>Public Key</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter public key" name="public_key"
                                                    value="{{ $paystackInfo['public_key'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Secret Key</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter secret key" name="secret_key"
                                                    value="{{ $paystackInfo['secret_key'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Charges</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter Charges" name="charges"
                                                    value="{{ $paystackInfo['charges'] }}" />
                                                <input type="hidden" class="form-control form-control-lg"
                                                    name="updatePaystack" value="updatePaystack" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Charges Type</label>
                                                <select class="form-control form-control-lg" name="chargestype">
                                                    <option value="percentage"
                                                        {{ $paystackInfo['chargesType'] == 'percentage' ? "selected='selected'" : '' }}>
                                                        Percentage
                                                    </option>

                                                    <option value="flat_rate"
                                                        {{ $paystackInfo['chargesType'] == 'flat_rate' ? "selected='selected'" : '' }}>
                                                        Flat Rate
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group mb-2">
                                                <label class="custom-switch">
                                                    <span class="custom-switch-description mr-2 fs-18">Allow
                                                        Paystack</span>
                                                    <input type="checkbox" name="status" class="custom-switch-input"
                                                        {{ $paystackInfo['status'] == 'active' ? 'checked' : '' }}>
                                                    <span
                                                        class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Airtime Vending Limit</h3>
                                </div>
                                <form method="post" action="{{ route('update-airtime-info') }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-12 mb-2">
                                                <label>Minimum Value</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter minimum airtime amount" name="min_value"
                                                    value="{{ $airtimeInfo['min_value'] }}" />
                                            </div>

                                            <div class="col-md-12 mb-2">
                                                <label>Maximum Value</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter maximum airtime amount" name="max_value"
                                                    value="{{ $airtimeInfo['max_value'] }}" />
                                                <input class="form-control form-control-lg" type="hidden"
                                                    name="updateAiritmeInfo" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Conversion of Airtime</h3>
                                </div>
                                <form method="post" action="{{ route('update-airtime-conversion') }}">
                                    @method('PUT')
                                    @csrf

                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-12 mr-0 border-bottom">
                                                <label class="custom-switch">
                                                    <span class="custom-switch-description mr-2 fs-18">Conversion
                                                        Status</span>
                                                    <input type="checkbox" name="status" class="custom-switch-input"
                                                        {{ $conversionSettingStatus == 1 ? 'checked' : '' }}>
                                                    <span
                                                        class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                </label>
                                                <input class="form-control form-control-lg" type="hidden"
                                                    name="updateAiritmeConversion" />
                                            </div>

                                            <hr />

                                            <div class="col-md-12 mb-2 mt-4">
                                                <h4>MTN Conversion</h4>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Airtime Receiver</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter mtn airtime receiver" name="mtn_receiver"
                                                    value="{{ $mtnConversion['receiver'] }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Percentage</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter conversion percentage" name="mtn_percentage"
                                                    value="{{ $mtnConversion['percentage'] }}" />
                                            </div>


                                            <div class="col-md-12 mb-2">
                                                <h4>Airtel Conversion</h4>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Airtime Receiver</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter mtn airtime receiver" name="airtel_receiver"
                                                    value="{{ $airtelConversion['receiver'] }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Percentage</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter conversion percentage" name="airtel_percentage"
                                                    value="{{ $airtelConversion['percentage'] }}" />
                                            </div>


                                            <div class="col-md-12 mb-2">
                                                <h4>Glo Conversion</h4>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Airtime Receiver</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter mtn airtime receiver" name="glo_receiver"
                                                    value="{{ $gloConversion['receiver'] }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Percentage</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter conversion percentage" name="glo_percentage"
                                                    value="{{ $gloConversion['percentage'] }}" />
                                            </div>


                                            <div class="col-md-12 mb-2">
                                                <h4>9mobile Conversion</h4>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Airtime Receiver</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter 9mobile airtime receiver" name="eti_receiver"
                                                    value="{{ $etiConversion['receiver'] }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label>Percentage</label>
                                                <input class="form-control form-control-lg"
                                                    placeholder="Enter conversion percentage" name="eti_percentage"
                                                    value="{{ $etiConversion['percentage'] }}" />
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col">
                                                    <label class="custom-switch">
                                                        <span class="custom-switch-description fs-18">MTN Status</span>
                                                        <input type="checkbox" name="mtnStatus"
                                                            class="custom-switch-input"
                                                            {{ $mtnConversion['status'] == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                    </label>
                                                </div>

                                                <div class="col">
                                                    <label class="custom-switch">
                                                        <span class="custom-switch-description fs-18">Airtel
                                                            Status</span>
                                                        <input type="checkbox" name="airtelStatus"
                                                            class="custom-switch-input"
                                                            {{ $airtelConversion['status'] == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                    </label>
                                                </div>

                                                <div class="col">
                                                    <label class="custom-switch">
                                                        <span class="custom-switch-description fs-18">Glo Status</span>
                                                        <input type="checkbox" name="gloStatus"
                                                            class="custom-switch-input"
                                                            {{ $gloConversion['status'] == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                    </label>
                                                </div>

                                                <div class="col">
                                                    <label class="custom-switch">
                                                        <span class="custom-switch-description fs-18">9mobile
                                                            Status</span>
                                                        <input type="checkbox" name="etiStatus"
                                                            class="custom-switch-input"
                                                            {{ $etiConversion['status'] == 1 ? 'checked' : '' }}>
                                                        <span
                                                            class="custom-switch-indicator custom-switch-indicator-lg"></span>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="text-center">
                                            <button class="btn btn-danger btn-lg btn-block">Submit</button>
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

    @include('components.footer-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>