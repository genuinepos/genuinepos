<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit'). ' ' . App\Enums\ContactType::tryFrom($type)->name }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_contact_form" action="{{ route('contacts.update', [$contact->id, $contact->type]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                                    <input required type="text" name="name" class="form-control" id="contact_name" data-next="contact_phone" value="{{ $contact->name }}" placeholder="@lang('menu.customer_name')" />
                                    <span class="error error_contact_name"></span>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.phone') </strong> <span class="text-danger">*</span></label>
                                    <input required type="text" name="phone" class="form-control" id="contact_phone" value="{{ $contact->phone }}" data-next="contact_business_name" placeholder="@lang('menu.phone_number')" />
                                    <span class="error error_contact_phone"></span>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.business_name') </strong></label>
                                    <input type="text" name="business_name" class="form-control" id="contact_business_name" value="{{ $contact->business_name }}" data-next="contact_alternative_phone" placeholder="@lang('menu.business_name')" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.alternative_number') </strong> </label>
                                    <input type="text" name="alternative_phone" class="form-control" id="contact_alternative_phone" value="{{ $contact->alternative_phone }}" data-next="contact_landline" placeholder="Alternative phone number" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.landline') </strong></label>
                                    <input type="text" name="landline" class="form-control" id="contact_landline" value="{{ $contact->landline }}" data-next="contact_email" placeholder="landline number" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.email') </strong></label>
                                    <input type="text" name="email" class="form-control" id="contact_email" value="{{ $contact->email }}" data-next="contact_tax_number" placeholder="Email address" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.tax_number') </strong></label>
                                    <input type="text" name="tax_number" class="form-control" id="contact_tax_number" value="{{ $contact->tax_number }}" data-next="contact_customer_group_id" placeholder="@lang('menu.tax_number')" />
                                </div>

                                @if ($type == \App\Enums\ContactType::Customer->value)
                                    <div class="col-md-4">
                                        <label><strong>@lang('menu.customer_group') </strong> </label>
                                        <select name="customer_group_id" class="form-control" id="contact_customer_group_id" data-next="contact_date_of_birth">
                                            <option value="">@lang('menu.none')</option>
                                            @foreach ($customerGroups as $group)
                                                <option {{ $group->id == $contact->id ? 'SELECTED' : '' }} value="{{ $group->id }}">{{ $group->group_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.date_of_birth')</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">
                                                <i class="fas fa-calendar-week input_f"></i>
                                            </span>
                                        </div>

                                        <input type="text" name="date_of_birth" class="form-control" id="contact_date_of_birth" data-next="contact_address" value="{{ $contact->date_of_birth }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label><strong>@lang('menu.address') </strong> </label>
                                    <input type="text" name="address" class="form-control" id="contact_address" value="{{ $contact->address }}" data-next="contact_city" placeholder="Address">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.city') </strong> </label>
                                    <input type="text" name="city" class="form-control" id="contact_city" value="{{ $contact->city }}" data-next="contact_state" placeholder="@lang('menu.city')"/>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.state') </strong> </label>
                                    <input type="text" name="state" class="form-control" id="contact_state" value="{{ $contact->state }}" data-next="contact_country" placeholder="@lang('menu.state')"/>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>@lang('menu.country') </strong> </label>
                                    <input type="text" name="country" class="form-control" id="contact_country" value="{{ $contact->country }}" data-next="contact_zip_code" placeholder="@lang('menu.country')"/>
                                </div>

                                <div class="col-md-4 mt-1">
                                    <label><strong>@lang('menu.zip_code') </strong> </label>
                                    <input type="text" name="zip_code" class="form-control" id="contact_zip_code" value="{{ $contact->zip_code }}" data-next="contact_shipping_address" placeholder="zip_code" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label><strong>@lang('menu.shipping_address') </strong> </label>
                                    <input type="text" name="shipping_address" class="form-control" id="contact_shipping_address" value="{{ $contact->shipping_address }}" data-next="contact_credit_limit" placeholder="@lang('menu.shipping_address')" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3" style="border-left:1px solid #000;">
                            <div class="row">
                                @if ($type == \App\Enums\ContactType::Customer->value)
                                    <div class="col-md-12">
                                        <label><strong>@lang('menu.credit_limit') </strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="If there is no credit limit of this customer, so leave this field empty." class="fas fa-info-circle tp"></i></label>
                                        <input type="number" step="any" name="credit_limit" class="form-control fw-bold" id="contact_credit_limit" value="{{ $contact->credit_limit }}" data-next="contact_pay_term_number" placeholder="@lang('menu.credit_limit')"/>
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label><strong>@lang('menu.pay_term') </strong> </label>
                                    <div class="input-group">
                                        <input type="text" name="pay_term_number" class="form-control fw-bold" id="contact_pay_term_number" value="{{ $contact->pay_term_number }}" data-next="contact_pay_term" placeholder="Number"/>
                                        <select name="pay_term" class="form-control" id="contact_pay_term" data-next="contact_opening_balance">
                                            <option value="">@lang('menu.select_term')</option>
                                            <option value="1">@lang('menu.days') </option>
                                            <option {{ $contact->pay_term == 2 ? 'SELECTED' : '' }} value="2">@lang('menu.months')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12 mt-1">
                                    <label><strong>@lang('menu.opening_balance') </strong></label>
                                    <div class="input-group">
                                        @php
                                            $openingBalanceAmount = $contact?->openingBalance ? $contact?->openingBalance?->amount : $contact?->opening_balance;
                                            $openingBalanceType = $contact?->openingBalance ? $contact?->openingBalance?->amount_type : $contact?->opening_balance_type;
                                        @endphp
                                        <input type="number" step="any" name="opening_balance" class="form-control fw-bold" id="contact_opening_balance" value="{{ $openingBalanceAmount }}" data-next="contact_opening_balance_type" placeholder="@lang('menu.opening_balance')"/>
                                        <select name="opening_balance_type" class="form-control" id="contact_opening_balance_type" data-next="contact_save_changes_btn">
                                            @if ($type == \App\Enums\ContactType::Customer->value)
                                                <option value="dr">{{ __('(+) Debit') }}
                                                </option>
                                                <option {{ $openingBalanceType == 'cr' ? 'SELECTED' : '' }} value="cr">{{ __('(-) Credit') }}</option>
                                            @else
                                                <option value="cr">{{ __('(+) Credit') }}</option>
                                                <option {{ $openingBalanceType == 'dr' ? 'SELECTED' : '' }} value="dr">{{ __('(-) Debit') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button contact_loading_button d-hide"><i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="contact_save_changes_btn" class="btn btn-sm btn-success contact_submit_button">{{ __("Save Changes") }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

        $('.contact_submit_button').prop('type', 'button');
    });

    var isAllowSubmit = true;
    $(document).on('click', '.contact_submit_button',function () {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        }else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_contact_form').on('submit',function(e) {
        e.preventDefault();

        $('.contact_loading_button').show();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                $('.error').html('');
                toastr.success(data);
                $('.contact_loading_button').hide();
                $('#addOrEditContactModal').modal('hide');

                contactTable.ajax.reload();
            },error : function(err) {

                $('.contact_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }

                toastr.error('Please check again all form fields.', 'Some thing went wrong.');

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_contact_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress click', 'select', function(e){

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#'+nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e){

        var nextId = $(this).data('next');

        if (e.which == 13) {

            if (nextId == 'contact_customer_group_id' && $('#contact_customer_group_id').val() == undefined) {

                $('#contact_date_of_birth').focus().select();
                return;
            }

            if (nextId == 'contact_credit_limit' && $('#contact_credit_limit').val() == undefined) {

                $('#contact_pay_term_number').focus().select();
                return;
            }

            $('#'+nextId).focus().select();
        }
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('contact_date_of_birth'),
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
