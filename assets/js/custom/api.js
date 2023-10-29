function vendorResult(response, vendorInfoField) {
    
    const vendorRequirement = response.vendor_requirement;
    requirementSplit = vendorRequirement.split(",");

    inputField = "";
    requirementSplit.forEach((requirement) => {
        if (requirement === "username") {
            inputField +=
                "<label><b>API Username</b></label> <input name='api_username' placeholder='Enter API username' class='form-control mb-2'>";
        }

        if (requirement === "password") {
            inputField +=
                "<label><b>API Password</b></label> <input name='api_password' placeholder='Enter API password' class='form-control mb-2'>";
        }

        if (requirement === "private_key") {
            inputField =
                "<label><b>API Private Key</b></label> <input name='api_private_key' placeholder='Enter API Private Key' class='form-control mb-2'>";
        }

        if (requirement === "public_key") {
            inputField +=
                "<label><b>API Public Key</b></label> <input name='api_public_key' placeholder='Enter API Public Key' class='form-control mb-2'>";
        }

        if (requirement === "secret_key") {
            inputField +=
                "<label><b>API Secret Key</b></label> <input name='api_secret_key' placeholder='Enter API Secret Key' class='form-control mb-2'>";
        }
    });
    vendorInfoField.html(inputField);
}

const vendorInputField = $(".edit-api-vendor-data");

function displayModal(apiResult) {
    
    const allVendors = apiResult.allVendors;

    modalContent = vendorInput = deliveryOption = "";

    // Due to some reasons, instead of fetching API content once again when admin changes Vendor
    // if editing is required, let's store the current API information in an hidden field
    modalContent = `<input value='${apiResult.id}' name='apiId' type='hidden'>
        <input value='${JSON.stringify(
            apiResult
        )}' class='apiResult' type='hidden'>
        <input value='${
            apiResult.api_vendor_id
        }' class='currentVendorId' type='hidden'>
    `;
    modalContent += `<label><b>API Name</b></label> <input name='api_name' placeholder='Enter API Secret Key' value='${apiResult.api_name}' class='form-control mb-2'>`;

    let optionBox = "";
    $(".fetchVendorInfo").empty();
    for (i = 0; i < allVendors.length; i++) {
        const vendor = allVendors[i];

        optionBox = "<option value='" + vendor.id + "' data-id='" + apiResult.vendor.id + "'";
        if (vendor.id == apiResult.vendor.id) {
            optionBox += " selected";
        }
        optionBox += ">" + vendor.vendor_name + "</option>";

        $(".fetchVendorInfo").append(optionBox);
    }

    if (apiResult.api_username != null) {
        vendorInput += `<label><b>API Username</b></label> 
            <input name='api_username' placeholder='Enter API Username' value='${apiResult.api_username}' class='form-control mb-2'>`;
    }

    if (apiResult.api_password != null) {
        vendorInput += `<label><b>API Password</b></label> 
            <input name='api_password' placeholder='Enter API Password' value='${apiResult.api_password}' class='form-control mb-2'>`;
    }

    if (apiResult.api_public_key != null) {
        vendorInput += `<label><b>API Public Key</b></label> 
            <input name='api_public_key' placeholder='Enter API Public Key' value='${apiResult.api_public_key}' class='form-control mb-2'>`;
    }

    if (apiResult.api_private_key != null) {
        vendorInput += `<label><b>API Private Key</b></label> 
            <input name='api_private_key' placeholder='Enter API Private Key' value='${apiResult.api_private_key}' class='form-control mb-2'>`;
    }

    if (apiResult.api_secret_key != null) {
        vendorInput += `<label><b>API Secret Key</b></label> 
            <input name='api_secret_key' placeholder='Enter API Secret key' value='${apiResult.api_secret_key}' class='form-control mb-2'>`;
    }

    $(".deliveryRoute").empty();
    
    deliveryOption += "<option value='cron'";
    if (apiResult.api_delivery_route == "cron") {
        deliveryOption += " selected";
    }
    deliveryOption += ">Cron</option>" + "<option value='instant'";
    if (apiResult.api_delivery_route === "instant") {
        deliveryOption += " selected";
    }
    deliveryOption += ">Instant</option>";

    $(".deliveryRoute").append(deliveryOption);

    vendorInputField.html(vendorInput);

    $(".edit-api-data").html(modalContent);

    $("#editApiModal").modal("show");
}

function modifyModalVendorRequirement(vendorResult) {
    console.log(vendorResult);
    vendorRequirement = vendorResult.vendor_requirement;

    // Get hidden input fields that was store on API fetching...
    const currentVendorId = $(".currentVendorId").val();
    const apiResult = JSON.parse($(".apiResult").val());

    if (vendorRequirement == "" || vendorRequirement == "") {
        vendorInputField.html(`<span class='d-flex mb-2 fs-18'><strong class='text-danger'>Error: </strong>
                Vendor does not have any requirement
            </span>`);
    } else {
        requirementSplit = vendorRequirement.split(",");

        inputField = "";
        requirementSplit.forEach((requirement) => {
            if (requirement === "username") {
                inputField +=
                    "<label><b>API Username</b></label> <input name='api_username' placeholder='Enter API username' class='form-control mb-2'";
                if (vendorResult.id == currentVendorId) {
                    if (apiResult.api_username != null) {
                        inputField += "value='" + apiResult.api_username + "'";
                    }
                }
                inputField += ">";
            }

            if (requirement === "password") {
                inputField +=
                    "<label><b>API Password</b></label> <input name='api_password' placeholder='Enter API password' class='form-control mb-2'";
                if (vendorResult.id == currentVendorId) {
                    if (apiResult.api_password != null) {
                        inputField += "value='" + apiResult.api_password + "'";
                    }
                }
                inputField += ">";
            }

            if (requirement === "private_key") {
                inputField =
                    "<label><b>API Private Key</b></label> <input name='api_private_key' placeholder='Enter API Private Key' class='form-control mb-2'";
                if (vendorResult.id == currentVendorId) {
                    if (apiResult.api_private_key != null) {
                        inputField +=
                            "value='" + apiResult.api_private_key + "'";
                    }
                }
                inputField += ">";
            }

            if (requirement === "public_key") {
                inputField +=
                    "<label><b>API Public Key</b></label> <input name='api_public_key' placeholder='Enter API Public Key' class='form-control mb-2'";
                if (vendorResult.id == currentVendorId) {
                    if (apiResult.api_public_key != null) {
                        inputField +=
                            "value='" + apiResult.api_public_key + "'";
                    }
                }
                inputField += ">";
            }

            if (requirement === "secret_key") {
                inputField +=
                    "<label><b>API Secret Key</b></label> <input name='api_secret_key' placeholder='Enter API Secret Key' class='form-control mb-2'";
                if (vendorResult.id == currentVendorId) {
                    if (apiResult.api_secret_key != null) {
                        inputField +=
                            "value='" + apiResult.api_secret_key + "'";
                    }
                }
                inputField += ">";
            }
        });
        vendorInputField.html(inputField);
    }
}
