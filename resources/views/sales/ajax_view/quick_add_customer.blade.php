<form id="add_customer_form" action="{{ route('sales.pos.add.customer') }}">
    @csrf
    <div class="form-group row">
        <div class="col-md-3">
            <label><strong>Name :</strong>  <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control c_add_input" data-name="Customer name" id="name" placeholder="Customer name"/>
            <span class="error error_name"></span>
        </div>

        <div class="col-md-3">
            <label><strong>Phone :</strong> <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control c_add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
            <span class="error error_phone"></span>
        </div>

        <div class="col-md-3">
            <label><strong>Contact ID :</strong></label>
            <input type="text" name="contact_id" class="form-control"  placeholder="Contact ID"/>
        </div>

        <div class="col-md-3">
            <label><strong>Business Name :</strong></label>
            <input type="text" name="business_name" class="form-control" placeholder="Business name"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><strong>Alternative Number :</strong>  </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="Alternative phone number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Landline :</strong></label>
            <input type="text" name="landline" class="form-control" placeholder="landline number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Email :</strong></label>
            <input type="text" name="email" class="form-control" placeholder="Email address"/>
        </div>

        <div class="col-md-3">
            <label><strong>Date Of Birth :</strong></label>
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
            <label><strong>Tax Number :</strong></label>
            <input type="text" name="tax_number" class="form-control" placeholder="Tax number"/>
        </div>

        <div class="col-md-3">
            <label><strong>Opening Balance :</strong>  </label>
            <input type="number" name="opening_balance" class="form-control" placeholder="Opening balance"/>
        </div>

        <div class="col-md-3">
            <label><strong>Pay Term :</strong>  </label>
            <div class="col-md-12">
                <div class="row">
                    <input type="text" name="pay_term_number" class="form-control w-50"/>
                    <select name="pay_term" class="form-control w-50">
                        <option value="1">Select term</option>
                        <option value="2">Days </option>
                        <option value="3">Months</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>Customer Group :</strong>  </label>
            <select name="customer_group_id" class="form-control" id="customer_group_id">
                <option value="">None</option>
                @foreach ($customerGroups as $customerGroup)
                    <option value="{{ $customerGroup->id }}">{{ $customerGroup->group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-9">
            <label><strong>Address :</strong>  </label>
            <input type="text" name="address" class="form-control"  placeholder="Address">
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-3">
            <label><strong>City :</strong>  </label>
            <input type="text" name="city" class="form-control" placeholder="City"/>
        </div>

        <div class="col-md-3">
            <label><strong>State :</strong>  </label>
            <input type="text" name="state" class="form-control" placeholder="State"/>
        </div>

        <div class="col-md-3">
            <label><strong>Country :</strong>  </label>
            <input type="text" name="country" class="form-control" placeholder="Country"/>
        </div>

        <div class="col-md-3">
            <label><strong>Zip-Code :</strong>  </label>
            <input type="text" name="zip_code" class="form-control" placeholder="zip_code"/>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-5">
            <label><strong>Shipping Address :</strong>  </label>
            <input type="text" name="shipping_address" class="form-control" placeholder="Shipping address"/>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>

<script>
    // Add customer by ajax
    $(document).on('submit', '#add_customer_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
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
            return;
        }

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                toastr.success('Customer added successfully.');
                $('.loading_button').hide();
                $('#addCustomerModal').modal('hide');
                $('#customer_id').append('<option value="'+data.id+'">'+ data.name +' ('+data.phone+')'+'</option>');
                $('#customer_id').val(data.id);
                $('#previous_due').val(parseFloat(data.total_sale_due).toFixed(2));
                calculateTotalAmount();
            }
        });
    });
</script>