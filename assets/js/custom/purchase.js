function getProducts(endpoint, outputField, isHide = true) {
    $.ajax({
        url: endpoint,
        type: "GET",
        beforeSend: function () {
            // outputField.addClass("d-none");
            outputField.append(
                "<p>Getting products... <i class='fa fa-spinner fa-spin fa-2x'></i></p>"
            );
        },
        success: function (result) {
            outputField.removeClass("d-none");
            outputField.find("p").remove();

            console.log(result);

            if (result != false) {
                let totalFee = 0;
                html = '<option value="">--Select--</option>';
                for (let i = 0; i < Object.keys(result).length; i++) {
                    productPricing =
                        result[i].productpricing == null
                            ? null
                            : result[i].productpricing;
                    sellingPrice =
                        productPricing == null
                            ? 0
                            : productPricing.selling_price;
                    extraCharge =
                        productPricing == null
                            ? 0
                            : productPricing.extra_charges;
                    totalFee =
                        parseFloat(sellingPrice) + parseFloat(extraCharge);
                    productName = result[i].product_name;

                    html +=
                        '<option value="' +
                        result[i].product_id +
                        '" data-price="' +
                        totalFee +
                        '" data-name="' +
                        productName +
                        '">';
                    html += productName;
                    html += "</option>";
                }
                outputField.find("select").html(html);
                outputField.find("select").attr("disabled", false); // Disabled the select field...
            } else {
                if (isHide) {
                    outputField.addClass("d-none");
                } else {
                    outputField.find("select").empty();
                    outputField
                        .find("select")
                        .html("<option value=''>-- Select --</option>");
                    outputField.find("select").attr("disabled", true); // Disabled the select field...
                }

                Swal.fire({
                    title: "Error",
                    text: "Products does not exit for this category",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        error: function (response, status, error) {
            // User has been logged out, so let's auto-refresh the page...
            if (response.status === 401) {
                window.location = "";
            }
        },
    });
}

function verifyCableTv_MeterNo(endpoint, verifyData, outputField, button) {
    $.ajax({
        type: "GET",
        url: endpoint,
        data: verifyData,
        beforeSend: function () {
            button.prop("disabled", true);
            outputField
                .addClass("text-success verification")
                .html("<i class='fa fa-spinner fa-spin'></i> Verifying Number");
        },
        success: function (response) {
            outputField.removeClass("text-success");
            if (response != false) {
                console.log(response);
                responseData = response.data;

                // Some property might be missing because not all provider has them...
                const hasCustomerNumber = responseData.hasOwnProperty(
                    "customer_number"
                )
                    ? "<strong>Customer Number: </strong>" +
                      responseData.customer_number +
                      "<br/>"
                    : "";

                // Cable tv property
                const hasDueAmount = responseData.hasOwnProperty("due_amount")
                    ? "<strong>Due Amount: </strong>" +
                      responseData.due_amount +
                      "<br/>"
                    : "";
                const hasStatus = responseData.hasOwnProperty("decoder_status")
                    ? "<strong>Status: </strong>" +
                      responseData.decoder_status +
                      "<br/>"
                    : "";
                const hasDueDate = responseData.hasOwnProperty("due_date")
                    ? "<strong>Due Date: </strong>" +
                      responseData.due_date +
                      "<br/>"
                    : "";
                const hasCustomerBalance = responseData.hasOwnProperty(
                    "customer_balance"
                )
                    ? "<strong>Balance: </strong>" +
                      responseData.customer_balance +
                      "<br/>"
                    : "";

                // Electricity property
                const hasOutstandingAmount = responseData.hasOwnProperty(
                    "outstanding_amount"
                )
                    ? "<strong>Outstanding Amount: </strong>" +
                      responseData.outstanding_amount +
                      "<br/>"
                    : "";
                const hasMinimumAmount = responseData.hasOwnProperty(
                    "minimum_amount"
                )
                    ? "<strong>Minimum Amount: </strong>" +
                      responseData.minimum_amount +
                      "<br/>"
                    : "";
                const hasCustomerAddress = responseData.hasOwnProperty(
                    "customer_address"
                )
                    ? "<strong>Address: </strong>" +
                      responseData.customer_address +
                      "<br/>"
                    : "";

                responseHtml = "";
                responseHtml +=
                    "<small class='fs-14'><strong>Customer: </strong>" +
                    responseData.customer_name +
                    "<br/>" +
                    hasCustomerAddress +
                    hasCustomerNumber +
                    hasDueAmount +
                    hasCustomerBalance +
                    hasStatus +
                    hasDueDate +
                    hasOutstandingAmount +
                    hasMinimumAmount +
                    "</small>";

                responseHtml +=
                    "<input type='hidden' name='customer_name' value='" +
                    responseData.customer_name +
                    "'>";
                if (hasCustomerNumber != "") {
                    responseHtml +=
                        "<input type='hidden' name='customer_number' value='" +
                        responseData.customer_number +
                        "'>";
                }

                // Address is needed for Electricity, if no value, just create the field so we can read it along the data...
                if (responseData.hasOwnProperty("customer_address")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_address' value='" +
                        responseData.customer_address +
                        "'>";
                }

                // Customer Ref ID is needed for Electricity
                if (responseData.hasOwnProperty("customer_reference_id")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_reference_id' value='" +
                        responseData.customer_reference_id +
                        "'>";
                }

                // Customer Details is needed for Electricity
                if (responseData.hasOwnProperty("customer_details")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_details' value='" +
                        responseData.customer_details +
                        "'>";
                }

                // Customer Details is needed for Electricity
                if (responseData.hasOwnProperty("customer_tariff_code")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_tariff_code' value='" +
                        responseData.customer_tariff_code +
                        "'>";
                }

                // Customer Access Code is needed for Electricity
                if (responseData.hasOwnProperty("customer_access_code")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_access_code' value='" +
                        responseData.customer_access_code +
                        "'>";
                }

                // Customer DT Number is needed for Electricity
                if (responseData.hasOwnProperty("customer_dt_number")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_dt_number' value='" +
                        responseData.customer_dt_number +
                        "'>";
                }

                // Customer Account type is needed for Electricity
                if (responseData.hasOwnProperty("customer_account_type")) {
                    responseHtml +=
                        "<input type='hidden' name='customer_account_type' value='" +
                        responseData.customer_account_type +
                        "'>";
                }

                outputField.addClass("verification").html(responseHtml);
                button.prop("disabled", false);
            } else {
                outputField.removeClass("verification").html("");

                Swal.fire({
                    title: "Error",
                    text: "Verification failed",
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
        error: function (response, status, error) {
            outputField.removeClass("verification").html("");

            if (response.status === 422) {
                if (response.responseJSON.message != undefined) {
                    // Handle other error cases
                    var errorMessage = response.responseJSON.message;

                    Swal.fire({
                        title: "Error",
                        html: errorMessage,
                        icon: "error",
                        confirmButtonText: "Ok",
                    });
                } else {
                    //Laravel validation case...
                    var errors = response.responseJSON.errors;

                    // Display the error messages
                    for (var field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            var errorMsg = errors[field][0];

                            Swal.fire({
                                title: "Error",
                                html: errorMsg,
                                icon: "error",
                                confirmButtonText: "Ok",
                            });
                        }
                    }
                }
            } else {
                // Handle other error cases
                var errorMessage = response.responseJSON.message;

                Swal.fire({
                    title: "Error",
                    html: errorMessage,
                    icon: "error",
                    confirmButtonText: "Ok",
                });
            }
        },
    });
}
