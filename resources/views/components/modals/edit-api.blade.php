<div class="modal" id="editApiModal">
    <div class="modal-dialog" role="document">

        <form method="POST" action="{{ route('update-api') }}">
            @method('PUT') @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="normalmodal1"><strong>Edit Plan</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="edit-api-data"></div>

                    <label><b>Select Vendor</b></label>
                    <select name="vendor_id" class="form-control form-control-lg mb-2 fetchVendorInfo">
                        <option value="">-- Select Delivery Route --</option>
                    </select>

                    <div class="edit-api-vendor-data"></div>

                    <label><b>Select Delivery Route</b></label>
                    <select name="api_delivery_route" class="form-control form-control-lg mb-2 deliveryRoute">
                        <option value="">-- Select Delivery Route --</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Update API</button>
                </div>
            </div>
        </form>
    </div>
</div>
