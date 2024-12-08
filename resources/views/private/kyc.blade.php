@include('components.head')

@php
    $ninCharge = $kycSettings !== false ? $kycSettings['nin'] : 0;
    $bvnCharge = $kycSettings !== false ? $kycSettings['bvn'] : 0;
    $verificationType = $kycSettings !== false ? $kycSettings['verification_type'] : "";
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
                            <h4 class="page-title"></h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                
                                <div class="card-header mb-5">
                                    <strong class="card-title">KYC Verification</strong>
                                </div>

                                <div class="card-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger" style="font-size: 18px">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col">
                                            
                                            <div class="alert alert-info" role="alert">
                                                <h4><strong>Fraud Protection</strong></h4>
                                                <p>
                                                    Safeguard your account against identity theft and fraudulent activities.

                                                    BVN verification helps us verify your identity, reducing the risk of unauthorized access.

                                                    Secure your account today — <em><strong>Provide your BVN for a safer online experience.</strong></em>
                                                </p>
                                            </div>

                                            <form method="post" action="{{ route('user.submit-kyc') }}">
                                                @csrf @method('PUT')
                                                <div class="">
                                                    <div class="form-group mb-3">
                                                        <label for="verification_method" class="form-label">Verification Method</label>
                                                        <select class="form-control verification_method" id="verification_method" name="verification_method">
                                                            <option value="">Select Verification Method</option>
                                                            @if ($use_bvn AND Str::contains($verificationType, 'bvn'))
                                                                <option value="bvn">Verify With BVN</option>
                                                            @endif
                                                            @if ($use_nin AND Str::contains($verificationType, 'nin'))
                                                                <option value="nin">Verify With NIN</option>
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="nin_layout d-none">
                                                        <div class="row">
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">NIN Full Name</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Provide your nin number on NIN" name="fullName">
                                                            </div>
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">NIN Number</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Provide your nin number" name="ninNumber">
                                                            </div>
        
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">NIN Phone Number</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Provide your NIN Mobile Number"
                                                                    name="ninPhoneNumber">
                                                            </div>
        
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">Date of Birth</label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="Provide your NIN Date of Birth" name="dateOfBirth">
                                                            </div>
                                                        </div>
                                                        <div class="nin_msg fs-18"></div>
                                                    </div>
        
                                                    <div class="bvn_layout d-none">
                                                        <div class="row">
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">BVN Full Name</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Provide full name on BVN" name="fullName">
                                                            </div>
        
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">BVN Number</label>
                                                                <input type="text" class="form-control" maxlength="11"
                                                                    placeholder="Provide your BVN Number: 22222000000"
                                                                    name="bvnNumber">
                                                            </div>
        
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">BVN Phone Number</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Provide your BVN Mobile Number"
                                                                    name="bvnPhoneNumber">
                                                            </div>
        
                                                            <div class="col-sm-6 mb-3">
                                                                <label class="form-label">Date of Birth</label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="Provide your BVN Date of Birth" name="dateOfBirth">
                                                            </div>
                                                        </div>
                                                        <div class="bvn_msg fs-18"></div>
                                                    </div>
                                                    
                                                </div>

                                                <button type="submit" class="btn btn-danger mt-4 mb-0">Submit</button>
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
        @include('components.footer-script')

        <script>
            $(".verification_method").on('change', function() {
                let bvnCharge = "{{ $bvnCharge }}"
                if ($(this).val() == 'bvn') {
                    $('.bvn_layout').removeClass('d-none');
                    $('.nin_layout').addClass('d-none');

                    // Disable inputs in BVN layout
                    $('.bvn_layout input').removeAttr('disabled');
                    // Enable inputs in BVN layout
                    $('.nin_layout input').attr('disabled', true);

                    let bvnCharge = "{{ $bvnCharge }}"
                    const bvnHTML =
                        `BVN Verification cost <strong class='text-danger'>NGN ${bvnCharge.toString()}</strong> which will be deducted from your wallet`
                    $('.bvn_msg').html(bvnHTML);
                } else if ($(this).val() == 'nin') {
                    $('.bvn_layout').addClass('d-none');
                    $('.nin_layout').removeClass('d-none');

                    // Disable inputs in NIN layout
                    $('.nin_layout input').removeAttr('disabled');
                    // Enable inputs in BVN layout
                    $('.bvn_layout input').attr('disabled', true);
                    
                    let ninCharge = "{{ $ninCharge }}"
                    const ninHTML =
                        `NIN Verification cost <strong class='text-danger'>NGN ${ninCharge.toString()}</strong> which will be deducted from your wallet`
                    $('.nin_msg').html(ninHTML);
                } else {
                    $('.bvn_layout').addClass('d-none');
                    $('.nin_layout').addClass('d-none');

                }
            });
        </script>