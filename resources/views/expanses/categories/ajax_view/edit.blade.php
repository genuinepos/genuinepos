<div class="section-header">
    <div class="col-md-6">
        <h6>@lang('menu.edit_expense_category')</h6>
    </div>
</div>

<div class="form-area px-3 pb-2">
    <form id="edit_category_form" action="{{ route('expenses.categories.update', $expenseCategory->id) }}">
        <div class="form-group row">
            <div class="col-md-12">
                <label><strong>@lang('menu.name') :</strong>  <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control edit_input" data-name="Category name" id="e_name" value="{{ $expenseCategory->name }}" placeholder="@lang('menu.expense_category')"/>
                <span class="error error_e_name"></span>
            </div>
        </div>

        <div class="form-group row text-right mt-2">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="btn-loading">
                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</b></button>
                    <button type="button" id="close_form" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save_changes')</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $('#edit_category_form').on('submit', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var request = $(this).serialize();
        var url = $(this).attr('action');
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                
                table.ajax.reload();
                toastr.success(data);
                $('.loading_button').hide();
                $('.error').html('');
                $('#add_form').show();
                $('#edit_form').hide();
            },error: function(err) {

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