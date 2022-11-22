<form id="edit_assset_type_form" action="{{ route('accounting.assets.asset.type.update', $type->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Type Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="asset_type_name" class="form-control" id="e_asset_type_name"
                placeholder="Asset Type name" value="{{ $type->asset_type_name }}"/>
            <span class="error error_e_asset_type_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Type Code :</b> </label>
            <input type="text" name="asset_type_code" class="form-control" placeholder="Asset Type Code" value="{{ $type->asset_type_code }}"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                <button type="submit" class="btn btn-sm btn-success">Save Change</button>
            </div>
        </div>
    </div>
</form>