<form id="edit_allowance_form" action="{{ route('hrm.allowance.update') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $allowance->id }}">
    <div class="form-group">
        <label><b>Description or Title :</b> <span class="text-danger">*</span></label>
        <input type="text" name="description" class="form-control form-control-sm" data-name="description"
            placeholder="Description or Title" value="{{ $allowance->description }}"/>
        <span class="error error_e_description"></span>
    </div>

    <div class="form-group">
        <label><b>Type :</b></label>
        <select class="form-control form-control-sm" name="type">
            <option {{ $allowance->type == 'Allowance' ? 'SELECTED' : '' }} value="Allowance">Allowance</option>
            <option {{ $allowance->type == 'Deduction' ? 'SELECTED' : '' }} value="Deduction">Deduction</option>
        </select>
    </div>

    <div class="row">
        <div class="form-group col-6">
            <label><b>Amount Type :</b> </label>
            <select class="form-control form-control-sm" name="amount_type">
                <option {{ $allowance->type == 1 ? 'SELECTED' : '' }} value="1">Fixed (0.0)</option>
                <option {{ $allowance->type == 2 ? 'SELECTED' : '' }} value="2">Percentage (%)</option>
            </select>
        </div>
        <div class="form-group col-6">
            <label><b>Amount : </b> <span class="text-danger">*</span></label>
            <input type="text" name="amount" class="form-control form-control-sm" placeholder="Amount"
                value="{{ $allowance->amount }}" />
            <span class="error error_e_amount"></span>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide">
                    <i class="fas fa-spinner"></i><span> Loading...</span>
                </button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">Close</button>
                <button type="submit" class="btn btn-sm btn-success">Save Change</button>
            </div>
        </div>
    </div>
</form>
