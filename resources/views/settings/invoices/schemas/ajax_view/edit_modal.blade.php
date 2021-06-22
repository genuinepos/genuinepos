 <!--begin::Form-->
 <form id="edit_schema_form" action="{{ route('invoices.schemas.update', $schema->id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label><b>Preview : <span id="e_schema_preview">#{{$schema->prefix.''.$schema->start_from}}</span></label>
    </div>

    <div class="form-group row">
        <div class="col-md-6">
            <label><b>Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="Schema name" value="{{ $schema->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-6">
            <label><b>Format :</b> <span class="text-danger">*</span></label>
            <select name="format" class="form-control form-control-sm" id="e_format">
                <option {{ $schema->format == 1 ? 'SELECTED' : ''  }} value="1">FORMAT-XXXX</option>
                <option {{ $schema->format == 2 ? 'SELECTED' : ''  }} value="2">FORMAT-{{ date('Y') }}/XXXX</option>
            </select>
            <span class="error error_e_format"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><b>Prefix :</b> <span class="text-danger">*</span></label>
            <input type="text" name="prefix" {{ $schema->format == 2 ? 'readonly' : ''  }} class="form-control form-control-sm" id="e_prefix" placeholder="Prefix" value="{{ $schema->prefix }}"/>
            <span class="error error_e_prefix"></span>
        </div>

        <div class="col-md-6">
            <label><b>Start From :</b></label>
            <input type="number" name="start_from" class="form-control form-control-sm" id="e_start_from" placeholder="Start From" value="{{ $schema->start_from }}"/>
        </div>
    </div>

    <div class="form-group text-end mt-3">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
        <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
    </div>
</form>