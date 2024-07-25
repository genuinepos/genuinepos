<x-saas::admin-layout title="Edit Plan">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Edit Plan') }}</h5>
                    <div>
                        <a href="{{ route('saas.plans.index') }}" class="btn btn-primary">{{ __('Plan List') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="edit_plan_form" action="{{ route('saas.plans.update', $plan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_trial_plan" value="{{ $plan->is_trial_plan }}">
                        <div class="row">
                            <div class="col-md-6">
                                @if ($plan->is_trial_plan == 0)
                                    <div class="mb-4">
                                        <label class="form-label">{{ __('Plan Type') }} <span class="text-danger">*</span></label>
                                        <select class="form-control" name="plan_type" id="plan_type">
                                            @foreach (\App\Enums\PlanType::cases() as $planType)
                                                <option @selected($plan->plan_type == $planType->value) value="{{ $planType->value }}">{{ $planType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label" class="form-label">{{ __('Plan Name') }}</label>
                                    <input required type="text" class="form-control" name="name" placeholder="{{ __("Enter Plan Name") }}" value="{{ $plan->name }}">
                                    <span class="text-danger error_name"></span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" class="form-label">{{ __('URL Slug') }} ({{ __('Keep empty to get auto-generated slug') }})</label>
                                    <input type="text" class="form-control" name="slug" placeholder="{{ __('Enter URL Slug') }}" value="{{ $plan->slug }}">
                                </div>

                                @if ($plan->is_trial_plan == 0)
                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Price Per Month') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="price_per_month" class="form-control" id="price_per_month" value="{{ $plan->price_per_month }}" placeholder="{{ __('Price Per Month') }}">
                                        <span class="text-danger error_price_per_month"></span>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Price Per Year') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="price_per_year" class="form-control" id="price_per_year" value="{{ $plan->price_per_year }}" placeholder="{{ __('Price Per Year') }}">
                                        <span class="text-danger error error_price_per_year"></span>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">{{ __('Is Enable Lifetime Period') }} <span class="text-danger">*</span></label>
                                        <select name="has_lifetime_period" class="form-control" id="has_lifetime_period">
                                            <option value="0">{{ __("No") }}</option>
                                            <option @selected($plan->has_lifetime_period == 1) value="1">{{ __("Yes") }}</option>
                                        </select>
                                    </div>

                                    <div class="mb-1 {{ $plan->has_lifetime_period == 0 ? 'd-none' : '' }} lifetime_field">
                                        <label class="form-label">{{ __('LifeTime Price') }} <span class="text-danger">*</span></label>
                                        <input {{ $plan->has_lifetime_period == 1 ? 'required' : '' }} type="number" name="lifetime_price" class="form-control lifetime-required-field" id="lifetime_price" value="{{ $plan->lifetime_price }}" placeholder="{{ __('Lifetime Price') }}">
                                        <span class="text-danger error error_lifetime_price"></span>
                                    </div>

                                    <div class="mb-1 {{ $plan->has_lifetime_period == 0 ? 'd-none' : '' }} lifetime_field">
                                        <label class="form-label">{{ __('Lifetime Applicable Years') }} <span class="text-danger">*</span></label>
                                        <input {{ $plan->has_lifetime_period == 1 ? 'required' : '' }} type="number" name="applicable_lifetime_years" class="form-control lifetime-required-field" id="applicable_lifetime_years" value="{{ $plan->applicable_lifetime_years }}" placeholder="{{ __('Applicable Years') }}">
                                        <span class="text-danger error error_applicable_lifetime_years"></span>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Company Price Per Month') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="business_price_per_month" value="{{ $plan->business_price_per_month }}" class="form-control" id="business_price_per_month" placeholder="{{ __('Company Price Per Month') }}">
                                        <span class="text-danger error error_business_price_per_month"></span>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Company Price Per Years') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="business_price_per_year" value="{{ $plan->business_price_per_year }}" class="form-control" id="business_price_per_year" placeholder="{{ __('Company Price Per Years') }}">
                                        <span class="text-danger error error_business_price_per_year"></span>
                                    </div>

                                    <div class="mb-1 {{ $plan->has_lifetime_period == 0 ? 'd-none' : '' }} lifetime_field">
                                        <label class="form-label">{{ __('Company lifetime Price') }} <span class="text-danger">*</span></label>
                                        <input {{ $plan->has_lifetime_period == 1 ? 'required' : '' }} type="number" name="business_lifetime_price" value="{{ $plan->business_lifetime_price }}" class="form-control lifetime-required-field" id="business_lifetime_price" placeholder="{{ __('Company lifetime Price') }}">
                                        <span class="text-danger error error_business_lifetime_price"></span>
                                    </div>
                                @else
                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Trial Days') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="trial_days" class="form-control" id="trial_days" value="{{ $plan->trial_days }}" placeholder="{{ __('Trial Days') }}">
                                        <span class="text-danger error error_trial_days"></span>
                                    </div>

                                    <div class="mb-1">
                                        <label class="form-label">{{ __('Trial Store Count') }} <span class="text-danger">*</span></label>
                                        <input type="number" name="trial_shop_count" class="form-control" id="trial_shop_count" value="{{ $plan->trial_shop_count }}" placeholder="{{ __('Trial Store Count') }}">
                                        <span class="text-danger error error_trial_shop_count"></span>
                                    </div>
                                @endif

                                <div class="mb-1">
                                    <label class="form-label" class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control editor" name="description" placeholder="Enter Description" rows="4">{{ $plan->description }}</textarea>
                                </div>

                                <div class="">
                                    <h6>{{ __('Assign Features') }}</h6>
                                </div>

                                <div class="mb-1">
                                    <div class="py-2">
                                        <input type="checkbox" class="form-check-input" name="select_all" id="select_all">
                                        <label class="form-label" class="form-check-label">
                                            {{ __('Select All Features') }}
                                        </label>
                                    </div>
                                    @php
                                        $planFeatures = $plan?->features;
                                    @endphp

                                    @foreach ($features as $key => $feature)

                                        <div class=" @if(!$feature && ($key == 'user_count' || $key == 'employee_count' || $key == 'cash_counter_count' || $key == 'warehouse_count')) ms-3 @endif" id="feature_{{ $key }}">
                                            @if($feature)
                                                <input type="checkbox" class="form-check-input checkbox-child" name="features[{{$key}}]" value="{{ $feature }}" id="{{ $key }}" {{ isset($planFeatures) && array_key_exists($key, $planFeatures) && $planFeatures[$key] == 1 ? 'checked' : '' }}/>
                                            @endif

                                            <label for="{{ $key }}">{{ str($key)->headline() }} </label>

                                            @if(!$feature)
                                                <input type="text" name="features[{{$key}}]" value="{{ isset($planFeatures) && array_key_exists($key, $planFeatures) ? $planFeatures[$key] : null }}" class="form-control my-1 w-75" id="{{ $key }}_input" placeholder="Enter {{ str($key)->headline() }}" />
                                            @endif
                                        </div>
                                    @endforeach
                                    {{-- @foreach ($features as $feature)
                                        @php
                                            $isEnabled = $plan->features->where('id', $feature->id)->first();
                                        @endphp
                                        <div>
                                            <input type="checkbox" class="form-check-input checkbox-child" name="feature_id[]" value="{{ $feature->id }}" id="{{ $feature->id }}" @if ($isEnabled) checked @endif />
                                            <label for="{{ $feature->id }}">
                                                {{ str($feature->name)->headline() }}
                                            </label>
                                        </div>
                                    @endforeach --}}
                                </div>

                                <div class="mb-4 p-3" style="border: 1px solid red;">
                                    <label for="status" class="form-label"><span class="text-danger">*</span>{{ __('Plan Status') }}</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="1" @selected($plan->status == 1)>{{ __("Active") }}</option>
                                        <option value="0" @selected($plan->status != 1)>{{ __("In-Active") }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button plan_loading_btn d-none"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                                        <button type="submit" id="plan_save" class="btn btn-sm btn-success plan_submit_button">{{ __("Save Changes") }}</button>
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
                // $('#feature_employee_count').hide();

                // $('#feature_warehouse_count').hide();
                // $('#feature_cash_counter_count').hide();

                // $('#hrm').change( function() {
                //     if($(this).is(":checked")) {
                //         $('#feature_employee_count').show();
                //     } else {
                //         $('#employee_count_input').val('');
                //         $('#feature_employee_count').hide();
                //     }
                // })

                // $('#setup').change( function() {
                //     if($(this).is(":checked")) {
                //         $('#feature_warehouse_count').show();
                //         $('#feature_cash_counter_count').show();
                //     } else {
                //         $('#feature_warehouse_count').hide();
                //         $('#feature_cash_counter_count').hide();
                //     }
                // })

                // $('#user_count_input').hide();
                // $('#user_count').change(function() {
                //     if($(this).is(":checked")) {
                //         $('#user_count_input').show();
                //     } else {
                //         $('#user_count_input').val('');
                //         $('#user_count_input').hide();
                //     }
                // });

                // $('#customer_count_input').hide();
                // $('#customer_count').change(function() {
                //     if($(this).is(":checked")) {
                //         $('#customer_count_input').show();
                //     } else {
                //         $('#customer_count_input').val('');
                //         $('#customer_count_input').hide();
                //     }
                // });

                 $('#has_lifetime_period').change(function() {

                    if($(this).val() == 1) {

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

                let allChild = document.querySelectorAll('.checkbox-child');
                for (let child of allChild) {

                    if (selectAll.checked) {

                        child.checked = true;
                    } else {

                        child.checked = false;
                    }
                }
            })

            var isAllowSubmit = true;
            $(document).on('click', '.plan_submit_button',function () {

                if (isAllowSubmit) {

                    $(this).prop('type', 'submit');
                }else {

                    $(this).prop('type', 'button');
                }
            });

            $('#edit_plan_form').on('submit',function(e) {
                e.preventDefault();

                $('.plan_loading_btn').removeClass('d-none');
                var url = $(this).attr('action');

                var request = $(this).serialize();
                $.ajax({
                    url : url,
                    type : 'post',
                    data: request,
                    success:function(data){

                        $('.plan_loading_btn').addClass('d-none');
                        $('.error').html('');
                        if(!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'ERROR');
                            return;
                        }

                        window.location = "{{ url()->previous() }}";
                        toastr.success(data);
                    }, error: function(err) {

                        $('.plan_loading_btn').addClass('d-none');
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

                        toastr.error(err.responseJSON.message);

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        </script>
    @endpush
</x-saas::admin-layout>
