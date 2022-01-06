<form id="edit_account_form" action="{{ route('accounting.accounts.update') }}" method="POST">
    <input type="hidden" name="id" id="id">
    <div class="form-group">
        <label><strong>Name :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm edit_input" data-name="Type name" id="e_name" placeholder="Account name"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Account Number :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="account_number" class="form-control form-control-sm edit_input" data-name="Type name" id="e_account_number" placeholder="Account number"/>
        <span class="error error_e_account_number"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Bank Name :</strong> <span class="text-danger">*</span> </label>
        <select name="bank_id" class="form-control form-control-sm edit_input" data-name="Bank name" id="e_bank_id">
            <option value="">Select Bank</option>    
        </select>
        <span class="error error_e_bank_id"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Account Type : </strong></label>
        <select name="account_type_id"  class="form-control form-control-sm" title="Select Type"  id="e_account_type_id">
            <option value="">Select Account type</option> 
        </select>
    </div>

    <div class="form-group mt-1">
        <label><strong>Remark :</strong></label>
        <input type="text" name="remark" id="e_remark" class="form-control form-control-sm" placeholder="Remark Type"/>
    </div>

    <div class="form-group text-end py-2">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
        <button type="submit" class="c-btn me-0 btn_blue float-end">Update</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
    </div>
</form>