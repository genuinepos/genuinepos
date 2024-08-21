<x-saas::admin-layout title="Create Plan">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create Plan') }}</h5>
                    <div>
                        <a href="{{ route('saas.plans.index') }}" class="btn btn-primary">{{ __('Plan List') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="add_plan_from" action="{{ route('saas.plans.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">{{ __('Plan Type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control" name="plan_type" id="plan_type">
                                        @foreach (\App\Enums\PlanType::cases() as $planType)
                                            <option value="{{ $planType->value }}">{{ $planType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Plan Name') }} <span class="text-danger">*</span></label>
                                    <input required type="text" class="form-control" name="name" placeholder="{{ __('Enter Plan Name') }}">
                                    <span class="text-danger error error_name"></span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ __('URL Slug') }} ({{ __('Keep empty to get auto-generated slug') }})</label>
                                    <input type="text" class="form-control" name="slug" placeholder="{{ __('Enter URL Slug') }}">
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Price Per Month') }} <span class="text-danger">*</span></label>
                                    <input required type="number" name="price_per_month" class="form-control" id="price_per_month" placeholder="{{ __('Price Per Month') }}">
                                    <span class="text-danger error_price_per_month"></span>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Price Per Year') }} <span class="text-danger">*</span></label>
                                    <input required type="number" name="price_per_year" class="form-control" id="price_per_year" placeholder="{{ __('Price Per Year') }}">
                                    <span class="text-danger error error_price_per_year"></span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ __('Is Enable Lifetime Period') }} <span class="text-danger">*</span></label>
                                    <select name="has_lifetime_period" class="form-control" id="has_lifetime_period">
                                        <option value="0">{{ __('No') }}</option>
                                        <option value="1">{{ __('Yes') }}</option>
                                    </select>
                                </div>

                                <div class="mb-1 d-none lifetime_field">
                                    <label class="form-label">{{ __('LifeTime Price') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="lifetime_price" class="form-control lifetime-required-field" id="lifetime_price" placeholder="{{ __('Lifetime Price') }}">
                                    <span class="text-danger error error_lifetime_price"></span>
                                </div>

                                <div class="mb-1 d-none lifetime_field">
                                    <label class="form-label">{{ __('Lifetime Applicable Years') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="applicable_lifetime_years" class="form-control lifetime-required-field" id="applicable_lifetime_years" placeholder="{{ __('Applicable Years') }}">
                                    <span class="text-danger error error_applicable_lifetime_years"></span>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Company price per month') }} <span class="text-danger">*</span></label>
                                    <input required type="number" name="business_price_per_month" class="form-control" id="business_price_per_month" placeholder="{{ __('Company Price Per Month') }}">
                                    <span class="text-danger error error_business_price_per_month"></span>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Company price per years') }} <span class="text-danger">*</span></label>
                                    <input required type="number" name="business_price_per_year" class="form-control" id="business_price_per_year" placeholder="{{ __('Company Price Per Year') }}">
                                    <span class="text-danger error error_business_price_per_year"></span>
                                </div>

                                <div class="mb-1 d-none lifetime_field">
                                    <label class="form-label">{{ __('Company price lifetime') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="business_lifetime_price" class="form-control lifetime-required-field" id="business_lifetime_price" placeholder="{{ __('Company Price Per lifetime') }}">
                                    <span class="text-danger error error_business_lifetime_price"></span>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control editor" name="description" placeholder="{{ __('Enter Description') }}" rows="4"></textarea>
                                </div>

                                <div class="">
                                    <h6>{{ __('Assign Features') }}</h6>
                                </div>

                                <div class="mb-1">
                                    <div class="py-2">
                                        <input type="checkbox" class="form-check-input" name="select_all" id="select_all">
                                        <label for="select_all" class="form-check-label">
                                            {{ __('Select All Features') }}
                                        </label>
                                    </div>

                                    @foreach ($features as $key => $feature)
                                        <div class=" @if (!$feature && ($key == 'user_count' || $key == 'employee_count' || $key == 'cash_counter_count' || $key == 'warehouse_count')) ms-3 @endif" id="feature_{{ $key }}">
                                            @if ($feature)
                                                <input type="checkbox" class="form-check-input checkbox-child" name="features[{{ $key }}]" value="{{ $feature }}" id="{{ $key }}" />
                                            @endif
                                            <label for="{{ $key }}">{{ str($key)->headline() }}</label>
                                            @if (!$feature)
                                                <input type="text" name="features[{{ $key }}]" class="form-control my-1 w-75" id="{{ $key }}_input" placeholder="enter {{ $key }} count" />
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mb-4 p-2" style="border: 1px solid red;">
                                    <label for="status" class="form-label"><span class="text-danger">*</span>{{ __('Plan Status') }}</label>
                                    <select required name="status" id="status" class="form-select">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('In-Active') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button plan_loading_btn d-none"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                                        <button type="submit" id="plan_save" class="btn btn-sm btn-success plan_submit_button">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('#feature_employee_count').hide();
                $('#feature_user_count').hide();

                $('#feature_warehouse_count').hide();
                $('#feature_cash_counter_count').hide();

                $('#hrm').change(function() {

                    if ($(this).is(":checked")) {

                        $('#feature_employee_count').show();
                    } else {

                        $('#employee_count_input').val('');
                        $('#feature_employee_count').hide();
                    }
                });

                $('#setup').change(function() {
                    if ($(this).is(":checked")) {
                        $('#feature_warehouse_count').show();
                        $('#feature_cash_counter_count').show();
                    } else {
                        $('#feature_warehouse_count').hide();
                        $('#feature_cash_counter_count').hide();
                    }
                })

                $('#users').change(function() {

                    if ($(this).is(":checked")) {

                        $('#feature_user_count').show();
                    } else {

                        $('#feature_user_count').val('');
                        $('#feature_user_count').hide();
                    }
                });

                $('#has_lifetime_period').change(function() {

                    if ($(this).val() == 1) {

                        $('.lifetime_field').removeClass('d-none');
                        $('.lifetime-required-field').prop('required', true);
                    } else {

                        $('.lifetime_field').addClass('d-none');
                        $('.lifetime-required-field').prop('required', false);
                    }
                });
            });

            const selectAll = document.getElementById('select_all');
            selectAll.addEventListener('click', function() {

                $('#feature_employee_count').show();
                $('#feature_warehouse_count').show();
                $('#feature_cash_counter_count').show();
                $('#feature_user_count').show();

                let allChild = document.querySelectorAll('.checkbox-child');
                for (let child of allChild) {

                    if (selectAll.checked) {

                        child.checked = true;
                    } else {

                        child.checked = false;
                    }
                }
            });

            var isAllowSubmit = true;
            $(document).on('click', '.plan_submit_button', function() {

                if (isAllowSubmit) {

                    $(this).prop('type', 'submit');
                } else {

                    $(this).prop('type', 'button');
                }
            });

            $('#add_plan_from').on('submit', function(e) {
                e.preventDefault();

                $('.plan_loading_btn').removeClass('d-none');
                var url = $(this).attr('action');

                var request = $(this).serialize();
                isAjaxIn = false;
                isAllowSubmit = false;
                $.ajax({
                    beforeSend: function() {
                        isAjaxIn = true;
                    },
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {

                        isAjaxIn = true;
                        isAllowSubmit = true;
                        $('.plan_loading_btn').addClass('d-none');
                        $('.error').html('');

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'ERROR');
                            return;
                        }

                        $('#add_plan_from')[0].reset();
                        toastr.success(data);
                    },
                    error: function(err) {

                        isAjaxIn = true;
                        isAllowSubmit = true;
                        $('.plan_loading_btn').addClass('d-none');
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connection Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        } else if (err.status == 403) {

                            toastr.error("{{ __('Access Denied') }}");
                            return;
                        }

                        toastr.error(err.responseJSON.message);

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });

                if (isAjaxIn == false) {

                    isAllowSubmit = true;
                }
            });
        </script>
    @endpush
</x-saas::admin-layout>
