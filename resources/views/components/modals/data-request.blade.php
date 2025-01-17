<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="normalmodal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('create-data-request') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="normalmodal1"><strong>Create Request</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="serviceType" class="form-label">Select Service</label>
                        <select class="form-control form-control-lg select2-show-search" name="product_id">
                            @if (count($dataProducts))
                                @foreach ($dataProducts as $dataProduct)
                                    <option value="{{ $dataProduct->product_id }}">
                                        {{ $dataProduct->product_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-2">
                            <label for="init_code" class="form-label">Init Code</label>
                            <input class="form-control form-control-lg" name="init_code" placeholder="Enter init code">
                        </div>
    
                        <div class="col-md-6 form-group mb-2">
                            <label for="wrap_code" class="form-label">Wrap Code</label>
                            <input class="form-control form-control-lg" name="wrap_code" placeholder="Enter wrap code">
                        </div>
    
                        <div class="col-md-6 form-group mb-2">
                            <label for="mobilenig" class="form-label">Mobile Nig</label>
                            <input class="form-control form-control-lg" name="mobilenig" placeholder="Enter mobilenig code">
                        </div>
    
                        <div class="col-md-6 form-group mb-2">
                            <label for="smeplug" class="form-label">Smeplug</label>
                            <input class="form-control form-control-lg" name="smeplug" placeholder="Enter smeplug code">
                        </div>
    
                        <div class="col-md-6 form-group mb-2">
                            <label for="ipay" class="form-label">Ipay</label>
                            <input class="form-control form-control-lg" name="ipay" placeholder="Enter ipay code">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Create Request</button>
                </div>
            </div>
        </form>
    </div>
</div>
