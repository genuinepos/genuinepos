<form id="edit_assset_form" action="{{ route('accounting.assets.update', $asset->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Asset Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="asset_name" class="form-control" id="e_asset_name"
                placeholder="Asset Type name" value="{{ $asset->asset_name }}"/>
            <span class="error error_e_asset_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Asset Type :</b> <span class="text-danger">*</span></label>
            <select name="type_id" class="form-control" id="e_type_id" >
            <option value="">Select Asset Type</option>
                @foreach ($types as $type)
                    <option {{ $type->id == $asset->type_id ? 'SELECTED' : '' }} value="{{ $type->id }}">{{ $type->asset_type_name }}</option>
                @endforeach
            </select>
        <span class="error error_e_type_id"></span>
        </div>
    </div>

    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
        <div class="form-group row mt-1">
            <div class="col-md-12">
                <label><b>Branch :</b> <span class="text-danger">*</span></label>
                <select name="branch_id" class="form-control" id="e_branch_id">
                    <option value="">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
                    @foreach ($branches as $br)
                        <option {{ $br->id == $asset->branch_id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name.'/'.$br->branch_code }}</option>
                    @endforeach
                </select>
                <span class="error error_e_branch_id"></span>
            </div>
        </div>
    @else
        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
    @endif
   

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Quantity :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="quantity" class="form-control" id="e_quantity"
                placeholder="Asset Quantity" value="{{ $asset->quantity }}"/>
            <span class="error error_e_quantity"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Per Unit Value :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="per_unit_value" class="form-control" id="e_per_unit_value"
                placeholder="Per Unit Value" value="{{ $asset->per_unit_value }}"/>
            <span class="error error_e_per_unit_value"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Total Value :</b> <span class="text-danger">*</span></label>
            <input type="number" step="any" name="total_value" class="form-control" id="e_total_value"
                placeholder="Total Asset Value" value="{{ $asset->total_value }}"/>
            <span class="error error_e_total_value"></span>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                <button type="submit" class="btn btn-sm btn-success">Save Change</button>
            </div>
        </div>
    </div>
</form>