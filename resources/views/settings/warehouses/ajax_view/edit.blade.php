 <!--begin::Form-->
 <form id="edit_warehouse_form" action="{{ route('settings.warehouses.update', $w->id) }}">
    @csrf
    <div class="form-group">
        <label><b>Warehouse Name :</b><span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control edit_input" data-name="Warehouse name" id="e_name" placeholder="Warehouse Name" value="{{ $w->warehouse_name }}"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>Warehouse Code :</b>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Warehouse code must be unique." class="fas fa-info-circle tp"></i></label>
        <input type="text" name="code" class="form-control edit_input" data-name="Warehouse code" id="e_code" placeholder="Warehouse code" value="{{ $w->warehouse_code }}"/>
        <span class="error error_e_code"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>Phone :</b>  <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control edit_input" data-name="Phone number" id="e_phone" placeholder="Phone number" value="{{ $w->phone }}"/>
        <span class="error error_e_phone"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>Address :</b>  </label>
        <textarea name="address" class="form-control" placeholder="Warehouse address" id="e_address" rows="3">{{ $w->address }}</textarea>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end">Save Changes</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_form">Close</button>
        </div>
    </div>
</form>