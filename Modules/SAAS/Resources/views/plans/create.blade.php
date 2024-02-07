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
                                    <label for="name" class="form-label">{{ __('Plan Name') }} <span class="text-danger">*</span></label>
                                    <input required type="text" class="form-control" name="name" placeholder="{{ __('Enter Plan Name') }}">
                                    <span class="text-danger error error_name"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('URL Slug') }} ({{ __('Keep empty to get auto-generated slug') }})</label>
                                    <input type="text" class="form-control" name="slug" placeholder="{{ __('Enter URL Slug') }}">
                                </div>

                                {{-- <div class="mb-4">
                                    <label for="period_unit" class="form-label">{{ __('Plan Period Unit') }} <span class="text-danger">*</span></label>
                                    <select required name="period_unit" id="period_unit" class="form-select">
                                        <option value="">{{ __('Select Plan Period Unit') }}</option>
                                        @foreach (\Modules\SAAS\Enums\PlanPeriod::cases() as $period)
                                            <option value="{{ $period->value }}">{{ $period->value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_period_unit"></span>
                                </div> --}}

                                <div class="mb-4">
                                    <label for="price_per_year">{{ __('Price Per Month') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="price_per_month" class="form-control" id="price_per_month" placeholder="{{ __('Price Per Month') }}">
                                    <span class="text-danger error_price_per_month"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="price_per_year">{{ __('Price Per Year') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="price_per_year" class="form-control" id="price_per_year" placeholder="{{ __('Price Per Year') }}">
                                    <span class="text-danger error error_price_per_year"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="lifetime_price">{{ __('Life Time Price') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="lifetime_price" class="form-control" id="lifetime_price" placeholder="{{ __('Lifetime Price') }}">
                                    <span class="text-danger error error_lifetime_price"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="applicable_lifetime_years">{{ __('Lifetime Applicable Years') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="applicable_lifetime_years" class="form-control" id="applicable_lifetime_years" placeholder="{{ __("Applicable Years") }}">
                                    <span class="text-danger error error_period_unit"></span>
                                </div>

                                <div class="mb-4">
                                    <label for="currency_code" class="form-label">{{ __('Select Currency') }}</label>
                                    <select required name="currency_id" id="currency_id" class="form-select">
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="mb-4">
                                    <label for="price" class="form-label">{{ __('Period Price') }} <span class="text-danger">*</span></label>
                                    <input required type="number" min="0" step="0.0001" class="form-control" name="price" placeholder="{{ __('Enter Price') }}">
                                    <span class="error error_price"></span>
                                </div> --}}

                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control editor" name="description" placeholder="{{ __('Enter Description') }}" rows="4"></textarea>
                                </div>

                                <div class="">
                                    <h6>{{ __('Assign Features') }}</h6>
                                </div>

                                <div class="mb-4">
                                    <div class="py-2">
                                        <input type="checkbox" class="form-check-input" name="select_all" id="select_all">
                                        <label for="select_all" class="form-check-label">
                                            {{ __('Select All Features') }}
                                        </label>
                                    </div>
                                    @foreach ($features as $feature)
                                        <div>
                                            <input type="checkbox" class="form-check-input checkbox-child" name="feature_id[]" value="{{ $feature->id }}" id="{{ $feature->id }}" />
                                            <label for="{{ $feature->id }}">{{ str($feature->name)->headline() }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mb-4 p-2" style="border: 1px solid red;">
                                    <label for="status" class="form-label"><span class="text-danger">*</span>{{ __('Plan Status') }}</label>
                                    <select required name="status" id="status" class="form-select">
                                        <option value="1">{{ __("Active") }}</option>
                                        <option value="0">{{ __("In-Active") }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button plan_loading_btn d-none"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __("Close") }}</button>
                                        <button type="submit" id="plan_save" class="btn btn-sm btn-success plan_submit_button">{{ __("Save") }}</button>
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
            });

            var isAllowSubmit = true;
            $(document).on('click', '.plan_submit_button',function () {

                if (isAllowSubmit) {

                    $(this).prop('type', 'submit');
                }else {

                    $(this).prop('type', 'button');
                }
            });

            $('#add_plan_from').on('submit',function(e) {
                e.preventDefault();

                $('.plan_loading_btn').removeClass('d-none');
                var url = $(this).attr('action');

                var request = $(this).serialize();
                isAjaxIn = false;
                isAllowSubmit = false;
                $.ajax({
                    beforeSend: function(){
                        isAjaxIn = true;
                    },
                    url : url,
                    type : 'post',
                    data: request,
                    success:function(data){

                        isAjaxIn = true;
                        isAllowSubmit = true;
                        $('.plan_loading_btn').addClass('d-none');
                        $('.error').html('');
                        if(!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg, 'ERROR');
                            return;
                        }

                        $('#add_plan_from')[0].reset();
                        toastr.success(data);
                    }, error: function(err) {

                        isAjaxIn = true;
                        isAllowSubmit = true;
                        $('.plan_loading_btn').addClass('d-none');
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if(err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        } else if(err.status == 403) {

                            toastr.error("{{ __('Access Denied') }}");
                            return;
                        }

                        toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

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
