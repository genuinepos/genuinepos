<div class="section-header">
    <div class="col-md-6">
        <h6>@lang('menu.edit_customer_group')</h6>
    </div>
</div>

<div class="form-area px-3 pb-2">
    <form id="edit_group_form" action="{{ route('contacts.customers.groups.update', $groups->id) }}" method="POST">
        @csrf
        <input type="hidden" name="id" id="id">
        <div class="form-group mt-2">
            <label><strong>@lang('menu.name') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input"
                data-name="Group name" id="e_name" placeholder="Group name" value="{{ $groups->group_name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="form-group mt-2">
            <label><strong>@lang('menu.calculation_percent') (%) :</strong></label>
            <input type="number" step="any" name="calculation_percent" class="form-control"
                id="e_calculation_percent" placeholder="@lang('menu.calculation_percent')"  value="{{ $groups->calc_percentage }}"/>
                <span class="error error_e_calculation_percent"></span>
        </div>

        <div class="form-group row mt-3">
            <div class="col-md-12 d-flex justify-content-end">
                <div class="btn-loading">
                    <button type="button" class="btn loading_button d-hide"><i
                            class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                    <button type="button" id="close_form" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('#edit_group_form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.loading_button').show();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                customerGroup_table.ajax.reload();
                $('.loading_button').hide();
                $('#add_form').show();
                $('#edit_form').hide();
            },
                error: function(err) {
                        $('.loading_button').hide();
                        $('.error').html('');
                        $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
        });
    });
</script>