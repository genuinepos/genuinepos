<form id="edit_customer_form" action="{{ route('contacts.customer.update') }}">
    @csrf
    <input type="hidden" name="id" value="{{ $customer->id }}">
    <div class="form-group row">
        <div class="col-md-3">
            <label><b>@lang('menu.name')</b> <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control edit_input" data-name="Customer name" id="e_name" placeholder="@lang('menu.customer_name')" value="{{ $customer->name }}"/>
            <span class="error error_e_name"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.phone')</b> : <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control edit_input" data-name="Phone number" placeholder="@lang('menu.phone_number')" value="{{ $customer->phone }}"/>
            <span class="error error_e_phone"></span>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.customer_id')</b> </label>
            <input readonly type="text" name="contact_id" class="form-control" placeholder="@lang('menu.customer_id')" value="{{ $customer->contact_id }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.business_name')</b> </label>
            <input type="text" name="business_name" class="form-control" placeholder="@lang('menu.business_name')" id="e_business_name" value="{{ $customer->business_name }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.alternative_number')</b> </label>
            <input type="text" name="alternative_phone" class="form-control" placeholder="Alternative phone number" value="{{ $customer->alternative_phone }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.landline')</b> </label>
            <input type="text" name="landline" class="form-control" placeholder="landline number" value="{{ $customer->landline }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.email')</b> </label>
            <input type="text" name="email" class="form-control" placeholder="Email address" value="{{ $customer->email }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.tax_number')</b> </label>
            <input type="text" name="tax_number" class="form-control" placeholder="Tax number" value="{{ $customer->tax_number }}"/>
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.opening_balance') </strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Opening balance will be added in this customer due." class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="opening_balance" class="form-control" id="e_opening_balance" placeholder="@lang('menu.opening_balance')" value="{{ $branchOpeningBalance ? $branchOpeningBalance->amount : 0.00 }}" />
        </div>

        <div class="col-md-3">
            <label><strong>@lang('menu.credit_limit') </strong> </label>
            <input type="number" step="any" name="credit_limit" class="form-control" id="e_credit_limit" placeholder="@lang('menu.credit_limit')" value="{{ $customerCreditLimit ? $customerCreditLimit->credit_limit : '' }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.pay_term')</b> </label>
            <div class="row">
                <div class="col-md-5">
                    <input type="text" name="pay_term_number" class="form-control" id="e_pay_term_number" value="{{ $customerCreditLimit ? $customerCreditLimit->pay_term_number : '' }}" placeholder="Number"/>
                </div>

                <div class="col-md-7">
                    <select name="pay_term" class="form-control">
                        <option value="">@lang('menu.select_term')</option>
                        <option {{  $customerCreditLimit ? ($customerCreditLimit->pay_term == 1 ? 'SELECTED' : '') : '' }} value="1">@lang('menu.days')</option>
                        <option {{  $customerCreditLimit ? ($customerCreditLimit->pay_term == 2 ? 'SELECTED' : '') : '' }} value="2">@lang('menu.months')</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.customer_group')</b> </label>
            <select name="customer_group_id" class="form-control">
                <option value="">@lang('menu.none')</option>
                @foreach ($groups as $group)
                    <option {{ $customer->customer_group_id == $group->id ? 'SELECTED' : ''  }} value="{{ $group->id }}">{{ $group->group_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.date_of_birth')</b> </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_i"></i></span>
                </div>
                <input type="text" name="date_of_birth" id="e_date_of_birth" class="form-control" autocomplete="off" value="{{ $customer->date_of_birth }}" placeholder="YYYY-MM-DD">
            </div>
        </div>

        <div class="col-md-6">
            <label><b>@lang('menu.address')</b> </label>
            <input type="text" name="address" class="form-control" placeholder="Address" value="{{ $customer->address }}">
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-3">
            <label><b>@lang('menu.city')</b> </label>
            <input type="text" name="city" class="form-control" placeholder="City" value="{{ $customer->city }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.state')</b> </label>
            <input type="text" name="state" class="form-control" placeholder="@lang('menu.state')" value="{{ $customer->state }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.country')</b> </label>
            <input type="text" name="country" class="form-control" placeholder="@lang('menu.country')" value="{{ $customer->country }}"/>
        </div>

        <div class="col-md-3">
            <label><b>@lang('menu.zip_code')</b> </label>
            <input type="text" name="zip_code" class="form-control" placeholder="zip_code" value="{{ $customer->zip_code }}"/>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-5">
            <label><b>@lang('menu.shipping_address')</b> </label>
            <input type="text" name="shipping_address" class="form-control" placeholder="@lang('menu.shipping_address')" value="{{ $customer->shipping_address }}"/>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button d-hide">
                    <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                </button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
            </div>
        </div>
    </div>
</form>

<script>
     // edit category by ajax
     $('#edit_customer_form').on('submit',function(e) {
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.edit_input');
        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#' + inputId).val();
            if (idValue == '') {

                countErrorField += 1;
                var fieldName = $('#' + inputId).data('name');
                $('.error_' + inputId).html(fieldName + ' is required.');
            }
        });

        if (countErrorField > 0) {

            $('.loading_button').hide();
            return;
        }

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                $('.loading_button').hide();
                table.ajax.reload();
                $('#editModal').modal('hide');
            }
        });
    });

     new Litepicker({
        singleMode: true,
        element: document.getElementById('e_date_of_birth'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'YYYY-MM-DD',
    });
</script>
