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

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">

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

                                    @include('components.user-balance')

                                    <form method="post" action="{{ route('user.submit-data-request') }}">
                                        @csrf

                                        <div class="form-group mb-4 dataVolume_section">
                                            <label for="dataVolume" class="form-label">Data Volume</label>
                                            <select class="form-control form-control-lg dataVolume" name="dataVolume">
                                                <option value="">-- Select Data Volume--</option>
                                                @if ($getDataVolumes)
                                                    @foreach ($getDataVolumes as $productInfo)
                                                        @php
                                                            $productPricing = $productInfo['productpricing'];
                                                            $sellingPrice = $productPricing['selling_price'] + $productPricing['extra_charges'];
                                                        @endphp
                                                        <option value="{{ $productInfo['product_id'] }}"
                                                            data-price="{{ $sellingPrice }}"
                                                            data-name="{{ $productInfo['product_name'] }}"
                                                        >
                                                            {{ $productInfo['product_name'] }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group mb-4 d-none amoutToPay_section">
                                            <label for="amount" class="form-label">Amount:</label>
                                            <input class="form-control form-control-lg amoutToPay" value="0"
                                                disabled>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="amount" class="form-label">Phone Number:</label>
                                            <input class="form-control form-control-lg phone_number" name="phone_number"
                                                placeholder="Enter Phone Number" maxlength="11">
                                        </div>

                                        <div class="form-group mb-4">
                                            @include('components.transact-pin')
                                        </div>

                                        <button type="submit" class="btn btn-danger btn-block btn-lg mt-4 mb-0 vendData">
                                            <i class="fa fa-paper-plane"></i> Buy Data Bundle
                                        </button>
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

    <script src="{{ asset('assets/js/custom/purchase.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0/dist/sweetalert2.all.min.js"></script>
    <script>
        $(".dataVolume").on('change', function() {
            let selectedVolume = $(".dataVolume option:selected");
            if (selectedVolume.val() == "") {
                $(".amoutToPay_section").addClass("d-none");
                swal.fire({
                    icon: "error",
                    title: "Error",
                    html: "Please select a data volume"
                })
            } else {
                $(".amoutToPay_section").removeClass("d-none");
                $(".amoutToPay").val(selectedVolume.attr('data-price'));
            }
        });

        // vending of 9mobile Data...
        $(".vendData").click(function(e) {
            button = $(this);

            e.preventDefault();

            var datavolume = $(".dataVolume option:selected").attr('data-name');
            var dataprice = $(".dataVolume option:selected").attr('data-price');
            var phoneNumber = $(".phone_number").val();
            var transactPin = $(".transactPin").val();

            var load_form = true; //Should form load...?

            if(load_form) {

                if(datavolume == undefined || phoneNumber == '' || transactPin == '') {
                    swal.fire({
                        icon: "info",
                        html: "Please fill all filed before proceeding to make purchase",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if(dataprice == '' || dataprice == undefined) {
                    swal.fire({
                        icon: "info",
                        html: "No valid price given for this product("+datavolume+")Please contact Admin",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if (transactPin == "0000") {
                    swal.fire({
                        icon: "info",
                        html: "Default transaction PIN (0000) cannot be used in making transaction",
                        title: "Error",
                        allowOutsideClick: false
                    })
                } else {
                    var chargeFee = dataprice;
                    chargeFee == undefined ? 0:chargeFee;

                    var form = $(this).parents('form');
                
                    swal.fire({
                        icon: "question",
                        html: "You are about to subscribe <b>"+datavolume.toUpperCase()+"</b> for <b>"+phoneNumber+"</b>. <br> <br> Total of <b>N"+numberWithCommas(chargeFee)+"</b> will be deducted from your wallet",
                        title: "Subscribe",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Subscribe',
                    }).then((result) => {
                        if (result.isConfirmed) { 
                            form.submit();
                            button.html("Please wait <i class='fa fa-spinner fa-spin'></i>").prop("disabled", true);
                        }
                    });
                }
            }
            return false;
        });
    </script>
