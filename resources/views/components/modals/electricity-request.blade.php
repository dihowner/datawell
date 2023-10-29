<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="normalmodal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('create-electricity-request') }}">
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
                            @if (count($electricityProducts))
                                @foreach ($electricityProducts as $electricityProduct)
                                    <option value="{{ $electricityProduct->product_id }}">
                                        {{ $electricityProduct->product_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="serviceType" class="form-label">Mobile Nig</label>
                        <input class="form-control form-control-lg" name="mobilenig" placeholder="Enter mobilenig code">
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
