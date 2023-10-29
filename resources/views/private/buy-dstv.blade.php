@include('components.head')

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

                                    <form method="post" action="{{ route('user.submit-cabletv-request') }}">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <label for="amount" class="form-label">Select Category</label>
                                                <select class="form-control form-control-lg packageCategory"
                                                    name="packageCategory">
                                                    <option value="">-- Select --</option>
                                                    @if (count($dstvCategories) > 0)
                                                        @foreach ($dstvCategories as $dstvCategory)
                                                            <option value="{{ $dstvCategory['category_name'] }}">
                                                                {{ $dstvCategory['category_name'] }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-4 product_section">
                                                <label for="packageOption" class="form-label">Select Bouquet</label>
                                                <select class="form-control form-control-lg packageOption"
                                                    name="packageOption">
                                                    <option value="">-- Select --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Amount section -->
                                        <div class="amount_section"></div>
                                        <!-- Amount section ends -->

                                        <div class="form-group mb-4">
                                            <label for="amount" class="form-label">Smartcard Number:</label>
                                            <input class="form-control form-control-lg smartcard_no" name="smartcard_no"
                                                placeholder="Enter Smartcard Number" maxlength="11">
                                        </div>

                                        <div class="form-group mb-4 verification_result"></div>

                                        <div class="form-group mb-4">
                                            @include('components.transact-pin')
                                        </div>

                                        <button type="submit" class="btn btn-danger btn-block btn-lg mt-4 mb-0 payBtn" disabled>
                                            <i class="fa fa-paper-plane"></i> Subscribe Decoder
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
        const verificationEndpoint = "{{ config('app.url') }}verify-cable-tv";

        $(".packageCategory").on('change', function() {
            let category = $(this).val();

            $(".amount_section").html(""); // Remove elements in amount div

            if (category == "" || category == undefined) {
                $(".product_section").find('select').attr("disabled", true);
                swal.fire({
                    icon: "error",
                    title: "Error",
                    html: "Please select a category"
                })
            } else {
                const endpoint = "{{ config('app.url') }}get-product-category/" + `${category}`;
                let outputField = $(".product_section");
                getProducts(endpoint, outputField, false);
            }
        });

        $(".packageOption").on('change', function() {
            let selectedVolume = $(".packageOption option:selected");

            // package category selected ???
            let selectedPackageCategory = $(".packageCategory option:selected").val().toLowerCase();

            console.log(selectedPackageCategory);

            let amountHtml = "";
            outputField = $(".amount_section");

            if (selectedVolume.val() == "") {
                $(".amountToPay_section").addClass("d-none");
                outputField.html("");
                swal.fire({
                    icon: "error",
                    title: "Error",
                    html: "Please select a package option"
                })
            } else if (selectedPackageCategory.indexOf('top') === -
                1) { //Top-up doesn't appear in the category name...
                $(".amountToPay_section").removeClass("d-none");
                $(".amountToPay").val(selectedVolume.attr('data-price'));

                amountHtml += `<div class='form-group mb-4 amountToPay_section'>
                            <label for='amount' class='form-label'>Amount:</label>
                            <input class='form-control form-control-lg amountToPay' value='${selectedVolume.attr('data-price')}' disabled>
                            </div>`

                outputField.html(amountHtml);
            } else { //Top-up appear in the category name...
                amountHtml += `<div class='row'>
                            <div class='col-md-6 mb-4'>
                                <label for='amount' class='form-label'>Enter Amount</label>
                                <input class='form-control form-control-lg amount' name='amount' min='1' type='number'>
                            </div>

                            <div class='col-md-6 mb-4'>
                                <label for='amount' class='form-label'>Payable Amount</label>
                                <input class='form-control form-control-lg amountToPay' value='0' disabled>
                            </div>
                            </div>`

                outputField.html(amountHtml);
            }

            // if package option was selected and user has already typed the smartcard number, let's verify since all field has been selected
            if (selectedVolume.val() != "" &&
                $(".packageCategory option:selected").val() != "" &&
                $(".smartcard_no").val() != "" && $(".smartcard_no").val().length > 7
            ) {
                const verifyData = {
                    "category": "cabletv",
                    "service": "dstv",
                    "smart_number": $(".smartcard_no").val()
                };
                verifyCableTv_MeterNo(verificationEndpoint, verifyData, $(".verification_result"), $(".payBtn"))
            }
        });

        $(document).on('keyup focusout', '.amount', async function() {
            let amount = ($(".amount").val() == "") ? 0 : $(".amount").val();

            extraFee = $(".packageOption option:selected").attr('data-price');
            let totalFee = 0;
            totalFee = parseFloat(amount) + parseFloat(extraFee);
            $(".amountToPay").val(totalFee);

        });

        $(document).on('focusout', '.smartcard_no', async function() {
            let smartCard = $(this).val();
            let selectedPackageCategory = $(".packageCategory option:selected").val();
            let selectedPackageOption = $(".packageOption option:selected").val();

            if (selectedPackageCategory == "") {
                swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Please select package category"
                })
            } else if (selectedPackageOption == "") {
                swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Please select package option"
                })
            } else {
                if (smartCard.length > 7) {
                    try {
                        const verifyData = {
                            "category": "cabletv",
                            "service": "dstv",
                            "smart_number": smartCard
                        };
                        verifyCableTv_MeterNo(verificationEndpoint, verifyData, $(".verification_result"), $(
                            ".payBtn"))
                    } catch (error) {
                        Swal.fire({
                            icon: "error",
                            title: "Error !",
                            html: error
                        });
                    }
                }
            }
        });

        // vending of Airtel Data...
        $(".payBtn").click(function(e) {
            button = $(this);

            e.preventDefault();

            var packageName = $(".packageOption option:selected").attr('data-name');
            var dataprice = $(".packageOption option:selected").attr('data-price');
            var smartNumber = $(".smartcard_no").val();
            var amountInput = $(".amount").val(); // For Add-on
            var transactPin = $(".transactPin").val();

            var load_form = true; //Should form load...?

            if (load_form) {

                if (packageName == undefined || smartNumber == '' || transactPin == '') {
                    swal.fire({
                        icon: "info",
                        html: "Please fill all filed before proceeding to make purchase",
                        title: "Missing field",
                        allowOutsideClick: false
                    })
                } else if (dataprice == '' || dataprice == undefined) {
                    swal.fire({
                        icon: "info",
                        html: "No valid price given for this product(" + packageName +
                            ")Please contact Admin",
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
                    amountInput = amountInput == undefined ? 0 : amountInput;
                    let totalFee = 0;
                    totalFee = parseFloat(amountInput) + parseFloat(dataprice);
                    var form = $(this).parents('form');

                    swal.fire({
                        icon: "question",
                        html: "You are about to subscribe <b>" + packageName.toUpperCase() + "</b> on <b>" +
                            smartNumber + "</b>. <br> <br> Total of <b>N" + numberWithCommas(totalFee) +
                            "</b> will be deducted from your wallet",
                        title: "Subscribe",
                        allowOutsideClick: false,
                        showCancelButton: true,
                        showLoaderOnConfirm: true,
                        confirmButtonText: 'Subscribe',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                            button.html("Please wait <i class='fa fa-spinner fa-spin'></i>").prop(
                                "disabled", true);
                        }
                    });
                }
            }
            return false;
        });
    </script>
