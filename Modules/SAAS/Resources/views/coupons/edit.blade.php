<x-saas::admin-layout title="Edit Coupon">
    @push('css')
        @include('saas::coupons.partials.css_partial.css')
    @endpush
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Edit Coupon') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.coupons.index') }}" class="btn btn-sm btn-primary">{{ __('All coupons') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.coupons.update', $coupon->id) }}" id="couponstoreForm" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PATCH') }}

                        <div class="row">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="code" class="form-label"><strong>{{ __('Coupon Code') }}</strong><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="code" value="{{ $coupon->code }}" class="form-control" autocomplete="off" id="code" placeholder="{{ __('Enter Coupon Code') }}" required>
                                    <button type="button" class="btn btn-primary" id="generate_code">{{ __('Generate') }}</button>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="start_date" class="form-label"><strong>{{ __('Start Date') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="start_date" id="start_date" value="{{ $coupon->start_date }}" autocomplete="off" class="form-control" placeholder="{{ __('Start Date') }}" required>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="end_date" class="form-label"><strong>{{ __('End Date') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="end_date" id="end_date" value="{{ $coupon->end_date }}" autocomplete="off" class="form-control" placeholder="{{ __('End Date') }}" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="percent" class="form-label"><strong>{{ __('Percentage %') }}</strong><span class="text-danger">*</span></label>
                                <input type="number" name="percent" value="{{ $coupon->percent }}" autocomplete="off" class="form-control" id="percent" placeholder="{{ __('Percentage') }}" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xxl-3 col-lg-3 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Minimum Purchase') }}</strong></label>
                                <button type="button" class="btn btn-sm btn-toggle minimum_purchase_class @if ($coupon->is_minimum_purchase == 1) active @endif" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="minimum_purchase_input" name="is_minimum_purchase" value="{{ $coupon->is_minimum_purchase }}">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6" style="@if ($coupon->is_minimum_purchase == 0) display:none @endif" id="is_minimum_purchase_id">
                                <label for="minimum_purchase_amount" class="form-label"><strong>{{ __('Minimum Purchase Amount') }}</strong></label>
                                <input type="number" name="minimum_purchase_amount" value="{{ $coupon->minimum_purchase_amount }}" autocomplete="off" class="form-control" id="minimum_purchase_amount" placeholder="{{ __('Minimum Purchase Amount') }}">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6">
                                <label for="phone" class="form-label"><strong>{{ __('Maximum Usage') }}</strong></label>
                                <button type="button" class="btn btn-sm btn-toggle maximum_usage_class @if ($coupon->is_maximum_usage == 1) active @endif" data-toggle="button" aria-pressed="true" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                                <input type="hidden" id="maximum_usage_input" name="is_maximum_usage" value="{{ $coupon->is_maximum_usage }}">
                            </div>

                            <div class="col-xxl-3 col-lg-3 col-sm-6" style="@if ($coupon->is_maximum_usage == 0) display:none @endif" id="is_maximum_usage_id">
                                <label for="no_of_usage" class="form-label"><strong>{{ __('No Of Usage') }}</strong></label>
                                <input type="number" name="no_of_usage" value="{{ $coupon->no_of_usage }}" autocomplete="off" class="form-control" id="no_of_usage" placeholder="{{ __('No Of Usage') }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="mt-3">
                                <input type="submit" class="btn btn-sm btn-primary float-end" value="{{ __('Save Changes') }}" />
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
