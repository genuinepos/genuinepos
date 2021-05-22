<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">Edit Shipment - ({{ $sale->invoice_id }})</h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
        class="fas fa-times"></span></a>
</div>
<div class="modal-body" id="edit_shipment_modal_body">
    <form id="edit_shipment_form" action="{{ route('sales.shipment.update', $sale->id) }}" method="post">
        @csrf
        <div class="form-group row">
            <div class="col-md-6">
                <label><strong>Shipment Details : </strong></label>
                <textarea name="shipment_details" class="form-control form-control-sm" id="shipment_details"  cols="30" rows="3" placeholder="Shipment Details">{{ $sale->shipment_details }}</textarea>
            </div>
    
            <div class="col-md-6">
                <label><strong>Shipment Address : </strong></label>
                <textarea name="shipment_address" class="form-control form-control-sm add_input" id="shipment_address" data-name="Shipment address" cols="30" rows="3" placeholder="Shipment Address">{{ $sale->shipment_address }}</textarea>
                <span class="error error_shipment_address"></span>
            </div>
        </div>
    
        <div class="form-group row">
            <div class="col-md-6">
                <label><strong>Shipment Status :</strong> </label>
                <select name="shipment_status" class="form-control form-control-sm add_input" id="shipment_status" data-name="Shipment status">
                    <option value="">Select Shipment Status</option>
                    <option {{ $sale->shipment_status == 1 ? 'SELECTED' : '' }} value="1">Ordered</option>
                    <option {{ $sale->shipment_status == 2 ? 'SELECTED' : '' }} value="2">Packed</option>
                    <option {{ $sale->shipment_status == 3 ? 'SELECTED' : '' }} value="3">Shipped</option>
                    <option {{ $sale->shipment_status == 4 ? 'SELECTED' : '' }} value="4">Delivered</option>
                    <option {{ $sale->shipment_status == 5 ? 'SELECTED' : '' }} value="5">Cancelled</option>
                </select>
                <span class="error error_shipment_status"></span>
            </div>
    
            <div class="col-md-6">
                <label><strong>Delivered To :</strong></label>
                <input type="text" name="delivered_to" id="delivered_to" class="form-control form-control-sm add_input" placeholder="Delivered To" value="{{ $sale->delivered_to }}" data-name="Delivered to">
                <span class="error error_delivered_to"></span>
            </div>                         
        </div>
    
        <div class="form-group row mt-3">
            <div class="col-md-12">
                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
            </div>
        </div>
    </form>
</div>
