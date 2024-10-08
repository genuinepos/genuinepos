<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit') . ' ' . __(App\Enums\ContactType::tryFrom($type)->name) }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <form id="edit_contact_form" action="{{ route('contacts.update', [$contact->id, $contact->type]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row mt-1">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>{{ __("Name") }} </strong> <span class="text-danger">*</span></label>
                                    <input required type="text" name="name" class="form-control" id="contact_name" data-next="contact_phone" value="{{ $contact->name }}" placeholder="{{ __("Name") }}" />
                                    <span class="error error_contact_name"></span>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("Phone") }} </strong> <span class="text-danger">*</span></label>
                                    <input required type="text" name="phone" class="form-control" id="contact_phone" value="{{ $contact->phone }}" data-next="contact_business_name" placeholder="{{ __("Phone Number") }}" />
                                    <span class="error error_contact_phone"></span>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("Business Name") }}</strong></label>
                                    <input type="text" name="business_name" class="form-control" id="contact_business_name" value="{{ $contact->business_name }}" data-next="contact_alternative_phone" placeholder="{{ __("Business Name") }}" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>{{ __("Alternative Phone Number") }}</strong> </label>
                                    <input type="text" name="alternative_phone" class="form-control" id="contact_alternative_phone" value="{{ $contact->alternative_phone }}" data-next="contact_landline" placeholder="{{ __("Alternative Phone Number") }}" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("Landline") }}</strong></label>
                                    <input type="text" name="landline" class="form-control" id="contact_landline" value="{{ $contact->landline }}" data-next="contact_email" placeholder="{{ __("Landline") }}" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("Email") }}</strong></label>
                                    <input type="text" name="email" class="form-control" id="contact_email" value="{{ $contact->email }}" data-next="contact_tax_number" placeholder="{{ __("Email Address") }}" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>{{ __("Tax Number") }}</strong></label>
                                    <input type="text" name="tax_number" class="form-control" id="contact_tax_number" value="{{ $contact->tax_number }}" data-next="contact_customer_group_id" placeholder="{{ __("Tax Number") }}" />
                                </div>

                                @if ($type == \App\Enums\ContactType::Customer->value)
                                    <div class="col-md-4">
                                        <label><strong>{{ __("Customer Group") }}</strong> </label>
                                        <select name="customer_group_id" class="form-control" id="contact_customer_group_id" data-next="contact_date_of_birth">
                                            <option value="">{{ __("None") }}</option>
                                            @foreach ($customerGroups as $group)
                                                <option {{ $group->id == $contact->id ? 'SELECTED' : '' }} value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <label><strong>{{ __("Date Of Birth") }}</strong></label>
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
                                    <label><strong>{{ __("Address") }}</strong> </label>
                                    <input type="text" name="address" class="form-control" id="contact_address" value="{{ $contact->address }}" data-next="contact_city" placeholder="{{ __("Address") }}">
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-4">
                                    <label><strong>{{ __("City") }}</strong></label>
                                    <input type="text" name="city" class="form-control" id="contact_city" value="{{ $contact->city }}" data-next="contact_state" placeholder="@lang('menu.city')" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("State") }}</strong></label>
                                    <input type="text" name="state" class="form-control" id="contact_state" value="{{ $contact->state }}" data-next="contact_country" placeholder="{{ __("State") }}" />
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __("Country") }}</strong> </label>
                                    <input type="text" name="country" class="form-control" id="contact_country" value="{{ $contact->country }}" data-next="contact_zip_code" placeholder="{{ __("Country") }}" />
                                </div>

                                <div class="col-md-4 mt-1">
                                    <label><strong>{{ __("Zip-Code") }}</strong> </label>
                                    <input type="text" name="zip_code" class="form-control" id="contact_zip_code" value="{{ $contact->zip_code }}" data-next="contact_shipping_address" placeholder="zip_code" />
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label><strong>{{ __("Shipping Address") }}</strong> </label>
                                    <input type="text" name="shipping_address" class="form-control" id="contact_shipping_address" value="{{ $contact->shipping_address }}" data-next="contact_credit_limit" placeholder="{{ __("Shipping Address") }}" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3" style="border-left:1px solid #000;">
                            <div class="row">
                                @if ($type == \App\Enums\ContactType::Customer->value)
                                    <div class="col-md-12">
                                        <label><strong>{{ __("Credit Limit") }}</strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __("If there is no credit limit of this customer, so leave this field empty.") }}" class="fas fa-info-circle tp"></i></label>
                                        <input type="number" step="any" name="credit_limit" class="form-control fw-bold text-end" id="contact_credit_limit" data-next="contact_pay_term_number" value="{{ $contact->credit_limit }}" placeholder="{{ __("Credit Limit") }}" />
                                    </div>
                                @endif
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <label><strong>{{ __("Pay-Term") }}</strong> </label>
                                    <div class="input-group">
                                        <input type="text" name="pay_term_number" class="form-control fw-bold text-end" id="contact_pay_term_number" value="{{ $contact->pay_term_number }}" data-next="contact_pay_term" placeholder="Number" />
                                        <select name="pay_term" class="form-control" id="contact_pay_term" data-next="contact_opening_balance">
                                            <option value="">{{ __("Select Term") }}</option>
                                            <option value="1">{{ __("Days") }}</option>
                                            <option {{ $contact->pay_term == 2 ? 'SELECTED' : '' }} value="2">{{ __("Months") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-12 mt-1">
                                    <label><strong>{{ __("Opening Balance") }}</strong></label>
                                    <div class="input-group">
                                        @php
                                            $openingBalanceAmount = $contact?->account?->accountOpeningBalance ? $contact?->account?->accountOpeningBalance?->opening_balance : $contact?->opening_balance;
                                            $openingBalanceType = $contact?->account?->accountOpeningBalance ? $contact?->account?->accountOpeningBalance?->opening_balance_type : $contact?->opening_balance_type;
                                        @endphp
                                        <input type="number" step="any" name="opening_balance" class="form-control fw-bold text-end" id="contact_opening_balance" value="{{ $openingBalanceAmount }}" data-next="contact_opening_balance_type" placeholder="0.00" />
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
                            <button type="button" class="btn loading_button contact_loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                            <button type="submit" id="contact_save_changes_btn" class="btn btn-sm btn-success contact_submit_button">{{ __('Save Changes') }}</button>
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
    $(document).on('click', '.contact_submit_button', function() {

        if (isAllowSubmit) {

            $(this).prop('type', 'submit');
        } else {

            $(this).prop('type', 'button');
        }
    });

    $('#edit_contact_form').on('submit', function(e) {
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
            },
            error: function(err) {

                $('.contact_loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }

                toastr.error(err.responseJSON.message);

                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_contact_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('change keypress click', 'select', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 0) {

            $('#' + nextId).focus().select();
        }
    });

    $(document).on('change keypress', 'input', function(e) {

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

            $('#' + nextId).focus().select();
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
