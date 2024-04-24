<x-saas::admin-layout title="Create Coupon">
    @push('css')
        @include('saas::coupons.partials.css_partial.css')
    @endpush
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create Coupon') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.coupons.index') }}" class="btn btn-sm btn-primary">{{ __('All coupons') }}</a>
                    </div>
                </div>
                <div class="panel-body">

                    <form method="POST" action="{{ route('saas.coupons.store') }}" id="couponstoreForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="code" class="form-label"><strong>{{ __('Coupon Code') }}</strong> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="code" value="{{ old('code') }}" class="form-control" autocomplete="off" id="code" placeholder="{{ __('Enter Coupon Code') }}" required>
                                    <button type="button" class="btn btn-primary" id="generate_code">{{ __('Generate') }}</button>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="start_date" class="form-label"><strong>{{ __('Start Date') }}</strong> <span class="text-danger">*</span></label>
                                <input type="text" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="off" class="form-control" placeholder="{{ __('Enter Start Date') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="end_date" class="form-label"><strong>{{ __('End Date') }}</strong> <span class="text-danger">*</span></label>
                                <input type="text" id="end_date" name="end_date" value="{{ old('end_date') }}" class="form-control" autocomplete="off" placeholder="{{ __('Enter End Date') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Percentage %') }}</strong> <span class="text-danger">*</span></label>
                                <input type="number" name="percent" value="{{ old('percent') }}" class="form-control" id="percent" autocomplete="off" placeholder="{{ __('Enter Percentage') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-xxl-3 col-lg-3 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Minimum Purchase') }}</strong></label>
                                <button type="button" class="btn btn-sm btn-toggle minimum_purchase_class" autocomplete="off" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="minimum_purchase_input" name="is_minimum_purchase" value="1">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6" style="display:none" id="is_minimum_purchase_id">
                                <label for="minimum_purchase_amount" class="form-label"><strong>{{ __('Minimum Purchase Amount') }}</strong></label>
                                <input type="number" name="minimum_purchase_amount" class="form-control" id="minimum_purchase_amount" autocomplete="off" placeholder="{{ __('Minimum Purchase Amount') }}">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Maximum Usage') }}</strong></label>
                                <button type="button" class="btn btn-sm btn-toggle maximum_usage_class" autocomplete="off" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="maximum_usage_input" name="is_maximum_usage" value="0">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6" style="display:none" id="is_maximum_usage_id">
                                <label for="no_of_usage" class="form-label"><strong>{{ __('No Of Usage') }}</strong></label>
                                <input type="number" name="no_of_usage" class="form-control" id="no_of_usage" autocomplete="off" placeholder="{{ __('no Of Usage') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="mt-3">
                                <input type="submit" class="btn btn-sm btn-primary float-end" value="{{ __('Save') }}" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        @include('saas::coupons.partials.js_partial.js')
    @endpush
</x-saas::admin-layout>
