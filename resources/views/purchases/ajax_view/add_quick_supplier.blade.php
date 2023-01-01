<form id="add_supplier_form" action="{{ route('contacts.supplier.store') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>@lang('menu.name') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control s_add_input" data-name="Supplier name" id="name" placeholder="@lang('menu.supplier_name')"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.phone') :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control s_add_input" data-name="Phone number" id="phone" placeholder="@lang('menu.phone_number')"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.supplier_id') :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="{{ __('Contact ID') }}"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.business_name') :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('menu.business_name')"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('menu.alternative_number') :</strong>  </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="Alternative Phone Number"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.landline') :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="landline Number"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.email') :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="Email Address"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.date_of_birth'):</strong>  </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week"></i></span>
                </div>
                <input type="text" name="date_of_birth" class="form-control date-picker" autocomplete="off">
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('menu.tax_number') :</strong>  </label>
            <input type="text" name="tax_number" class="form-control" placeholder="Tax Bumber"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.opening_balance') :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="@lang('menu.opening_balance')"/>
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
                            <option value="">@lang('menu.select_term')</option>
                            <option value="1">@lang('menu.days') </option>
                            <option value="2">@lang('menu.months')</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><strong>@lang('menu.address') :</strong></label>
            <input type="text" name="address" class="form-control"  placeholder="Address">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>@lang('menu.city') :</strong></label>
            <input type="text" name="city" class="form-control" placeholder="@lang('menu.city')"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.state') :</b></label>
            <input type="text" name="state" class="form-control" placeholder="@lang('menu.state')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.country') :</strong></label>
            <input type="text" name="country" class="form-control" placeholder="@lang('menu.country')"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.zip_code') :</strong></label>
            <input type="text" name="zip_code" class="form-control" placeholder="zip_code"/>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-5">
            <label><strong>@lang('menu.shipping_address') :</strong></label>
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
    // Add supplier by ajax
    $('#add_supplier_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.s_add_input');
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
            return;
        }

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#addSupplierModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Supplier Added Successfully.');
                $('#add_supplier_form')[0].reset();
                $('.loading_button').hide();
                $('#supplier_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#supplier_id').val(data.id);
                document.getElementById('search_product').focus();
            },error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }
            }
        });
    });

</script>
