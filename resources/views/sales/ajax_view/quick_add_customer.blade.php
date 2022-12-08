<form id="add_customer_form" action="{{ route('contacts.customer.store') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>@lang('menu.name') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control c_add_input" data-name="Customer name" id="name" placeholder="Customer name"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.phone') :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control c_add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>Contact ID :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="Contact ID"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.business_name') :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('menu.business_name')"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('menu.alternative_number') :</strong>  </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="Alternative phone number"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.landline') :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="landline number"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.email') :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="Email address"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.date_of_birth'):</strong></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('menu.tax_number') :</strong></label>
            <input type="text" name="tax_number" class="form-control" placeholder="@lang('menu.tax_number')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.opening_balance') :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="@lang('menu.opening_balance')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.credit_limit') :</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If there is no credit limit of this customer, so leave this field empty." class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="credit_limit" class="form-control"
                placeholder="@lang('menu.credit_limit')" value=""/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.pay_term') :</strong>  </label>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" name="pay_term_number" class="form-control" placeholder="Number"/>
                    </div>

                    <div class="col-md-7">
                        <select name="pay_term" class="form-control">
                            <option value="1">@lang('menu.select_term')</option>
                            <option value="2">@lang('menu.days')</option>
                            <option value="3">@lang('menu.months')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>@lang('menu.customer_group') :</strong>  </label>
            <select name="customer_group_id" class="form-control" id="customer_group_id">
                <option value="">@lang('menu.none')</option>
                @foreach ($customerGroups as $customerGroup)
                    <option value="{{ $customerGroup->id }}">{{ $customerGroup->group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-9">
            <label><strong>@lang('menu.address') :</strong>  </label>
            <input type="text" name="address" class="form-control"  placeholder="Address">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>@lang('menu.city') :</strong>  </label>
            <input type="text" name="city" class="form-control" placeholder="@lang('menu.city')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.state') :</strong>  </label>
            <input type="text" name="state" class="form-control" placeholder="@lang('menu.state')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.country') :</strong>  </label>
            <input type="text" name="country" class="form-control" placeholder="@lang('menu.country')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.zip_code') :</strong>  </label>
            <input type="text" name="zip_code" class="form-control" placeholder="zip_code"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-5">
            <label><strong>@lang('menu.shipping_address') :</strong>  </label>
            <input type="text" name="shipping_address" class="form-control" placeholder="@lang('menu.shipping_address')"/>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success submit_button">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>

<script>
    // Add customer by ajax
    $('#add_customer_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        $('.submit_button').prop('button');
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.c_add_input');
            $('.error').html('');
            var countErrorField = 0;

        $.each(inputs, function(key, val){

            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();

            if(idValue == ''){

                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){

            $('.loading_button').hide();
            $('.submit_button').prop('submit');
            return;
        }

        $('.submit_button').prop('type', 'button');
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#addCustomerModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Customer added successfully.');
                $('.loading_button').hide();
                $('#customer_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#customer_id').val(data.id);
                $('#display_pre_due').val(parseFloat(data.total_sale_due).toFixed(2));
                $('#previous_due').val(parseFloat(data.total_sale_due).toFixed(2));
                calculateTotalAmount();
            }
        });
    });
</script>
