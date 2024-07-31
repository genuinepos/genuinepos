<div class="modal-dialog col-40-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Edit Customer Group") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_customer_group_form" action="{{ route('contacts.customers.groups.update', $customerGroup->id) }}" method="post">
                @csrf
                <div class="form-group mt-2">
                    <label><strong>{{ __("Name") }}</strong> <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" data-next="cus_group_price_calculation_type" id="cus_group_name" value="{{ $customerGroup->name }}" placeholder="{{ __("Group name") }}"/>
                    <span class="error error_cus_group_name"></span>
                </div>

                <div class="form-group mt-2">
                    <label><strong>{{ __("Price Calculation Type") }}</strong></label>
                    <select name="price_calculation_type" class="form-control" id="cus_group_price_calculation_type" data-next="calculation_percentage">
                    @foreach (\App\Enums\CustomerGroupPriceCalculationType::cases() as $priceCalculationType)
                        <option {{ $priceCalculationType->value == $customerGroup->price_calculation_type ? 'SELECTED' : '' }} value="{{ $priceCalculationType->value }}">{{ $priceCalculationType->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group mt-2 calculation_percentage {{ $customerGroup->price_calculation_type == \App\Enums\CustomerGroupPriceCalculationType::SellingPriceGroup->value ? 'd-hide' : '' }}">
                    <label><strong>{{ __("Price Calculation Percentage") }} (%) </strong></label>
                    <input type="number" step="any" name="calculation_percentage" class="form-control fw-bold" id="cus_group_calculation_percentage" data-next="customer_group_save_changes" value="{{ $customerGroup->calculation_percentage }}" placeholder="{{ __("Price Calculation Percentage") }}" autocomplete="off" />
                </div>

                <div class="form-group mt-2 group_price_group_id {{ $customerGroup->price_calculation_type == \App\Enums\CustomerGroupPriceCalculationType::Percentage->value ? 'd-hide' : '' }}">
                    <label><strong>{{ __("Selling Price Group") }}</strong></label>
                    <select name="price_group_id" class="form-control" id="cus_group_price_group_id" data-next="customer_group_save_changes">
                        <option value="">{{ __("Default Selling Price") }}</option>
                        @foreach ($priceGroups as $priceGroup)
                            <option {{ $priceGroup->id == $customerGroup->price_group_id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row mt-2">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button customer_group_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                            <button type="submit" id="customer_group_save_changes" class="btn btn-sm btn-success customer_group_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.customer_group_submit_button').prop('type', 'button');
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            if ($(this).attr('id') == 'cus_group_price_calculation_type' && $('#cus_group_price_calculation_type').val() == 1) {

                $('.group_price_group_id').hide();
                $('.calculation_percentage').show();
                $('#cus_group_calculation_percentage').focus().select();
                return;
            }else if ($(this).attr('id') == 'cus_group_price_calculation_type' && $('#cus_group_price_calculation_type').val() == 2) {

                $('.group_price_group_id').show();
                $('.calculation_percentage').hide();
                $('#cus_group_price_group_id').focus().select();
                return;
            }

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#'+nextId).focus().select();
        }
    });

    var isAllowSubmit = true;
    $(document).on('click', '.customer_group_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_customer_group_form').on('submit',function(e) {
        e.preventDefault();

        $('.customer_group_loading_btn').show();
        var url = $(this).attr('action');

        $.ajax({
            url : url,
            type : 'post',
            data: new FormData(this),
            processData: false,
            cache: false,
            contentType: false,
            success:function(data){

                $('.customer_group_loading_btn').hide();
                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg, 'ERROR');
                    return;
                }

                toastr.success(data);
                $('#customerGroupAddOrEditModal').modal('hide');
                customerGroupsTable.ajax.reload();
            }, error: function(err) {

                $('.customer_group_loading_btn').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if(err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                } else if(err.status == 403) {

                    toastr.error("{{ __('Access Denied') }}");
                    return;
                }

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_cus_group_' + key + '').html(error[0]);
                });
            }
        });
    });
</script>
