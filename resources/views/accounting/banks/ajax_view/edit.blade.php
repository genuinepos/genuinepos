<form id="edit_bank_form" action="{{ route('accounting.banks.update', $banks->id) }}" method="POST">
    @csrf
    <input type="hidden" name="id" id="id">
    <div class="form-group">
        <label><b>@lang('menu.bank_name')</b> : <span class="text-danger">*</span></label>
        <input type="text" name="name" id="e_name" class="form-control form-control-sm edit_input" data-name="Bank name" id="e_name" placeholder="Bank name" value="{{ $banks->name }}"/>
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('menu.branch_name')</b> : <span class="text-danger">*</span></label>
        <input type="text" name="branch_name" class="form-control form-control-sm edit_input" data-name="Branch name" id="e_branch_name" placeholder="@lang('menu.branch_name')" value="{{ $banks->branch_name }}"/>
        <span class="error error_e_branch_name"></span>
    </div>

    <div class="form-group mt-1">
        <label><b>@lang('menu.bank_address')</b> </label>
        <textarea name="address" class="form-control form-control-sm" id="e_address" cols="10" rows="3" placeholder="@lang('menu.bank_address')"> {{ $banks->address }}</textarea>
    </div>

    <div class="form-group text-right mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i
                    class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                </button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save_changes')</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#edit_bank_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                $('.error').html('');
                toastr.success(data);
                bank_table.ajax.reload();
                $('#edit_bank_form')[0].reset();
                $('.loading_button').hide();
                $('#editModal').modal('hide');
            },error: function(err) {
                $('.error').html('');
                $('.loading_button').hide();
                if (err.status == 0) {
                    toastr.error('Net Connetion Error.');
                    return;
                }
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
