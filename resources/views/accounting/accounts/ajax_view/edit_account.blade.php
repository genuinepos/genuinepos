<form id="edit_account_form" action="{{ route('accounting.accounts.update', $account->id) }}" method="POST">
    <div class="form-group">
        <label><strong>Name :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control form-control-sm edit_input" data-name="Type name" id="e_name" placeholder="Account name" value="{{ $account->name }}"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Account Number :</strong> <span class="text-danger">*</span></label>
        <input type="text" name="account_number" class="form-control form-control-sm edit_input" data-name="Type name" id="e_account_number" placeholder="Account number" value="{{ $account->account_number }}"/>
        <span class="error error_e_account_number"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Bank Name :</strong> <span class="text-danger">*</span> </label>
        <select name="bank_id" class="form-control form-control-sm edit_input" data-name="Bank name" id="e_bank_id">
            <option value="">Select Bank</option>   
            @foreach ($banks as $bank)
                <option {{ $account->bank_id == $bank->id ? 'SELECTED' : ''}} value="{{ $bank->id }}">{{ $bank->name }}</option>
            @endforeach 
        </select>
        <span class="error error_e_bank_id"></span>
    </div>

    <div class="form-group mt-1">
        <label><strong>Remark :</strong></label>
        <input type="text" name="remark" id="e_remark" class="form-control form-control-sm" placeholder="Remark Type" value="{{ $account->remark }}"/>
    </div>

    <div class="form-group text-end py-2">
        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
        <button type="submit" class="c-btn me-0 btn_blue float-end">Update</button>
        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
    </div>
</form>

<script>
    // edit account type by ajax
    $('#edit_account_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
    
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                toastr.success(data);
                $('.loading_button').hide();
                getAllAccount();
                $('#editModal').modal('hide'); 
            }, error: function(err) {
                $('.submit_button').prop('type', 'submit');
                $('.loading_button').hide();
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>