<form id="point_settings_form" class="setting_form hide-all" action="{{ route('branches.settings.reward.point', $branch->id) }}" method="post">
    @csrf
    <div class="form-group">
        <h6 class="text-primary mb-3"><b>{{ __('Reward Point Settings') }}</b></h6>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-4">
            <div class="row ">
                <p class="checkbox_input_wrap">
                    <input type="checkbox" {{ $generalSettings['reward_point_settings__enable_cus_point'] == '1' ? 'CHECKED' : '' }} name="enable_cus_point"> &nbsp; <b>{{ __('Enable Reward Point') }}</b>
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Reward Point Display Name') }}</label>
            <input type="text" name="point_display_name" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__point_display_name'] }}">
        </div>
    </div>

    <div class="form-group row mt-2">
        <h6 class="text-primary mb-1"><b>{{ __('Earning Settings') }}</b></h6>
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Amount spend for unit point') }}
                <i data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __("Example: If you set it as 10, then for every $10 spent by customer they will get one reward points. If the customer purchases for $1000 then they will get 100 reward points") }}." class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="amount_for_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__amount_for_unit_rp'] }}">
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Minimum order total to earn reward') }} <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Example: If you set it as 100 then customer will get reward points only if there invoice total is greater or equal to 100. If invoice total is 99 then they won’t get any reward points.You can set it as minimum 1.') }}" class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="min_order_total_for_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_rp'] }}">
        </div>

        <div class="col-md-4">
            <label class="fw-bold">{{ __('Maximum points per order') }} <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Maximum reward points customers can earn in one invoice. Leave it empty if you don’t want any such restrictions.') }}" class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="max_rp_per_order" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_rp_per_order'] }}">
        </div>
    </div>

    <div class="form-group row mt-2">
        <h6 class="text-primary mb-1"><b>{{ __('Redeem Points Settings') }}</b></h6>
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Redeem amount per unit point') }}
                <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __("example: If 1 point is $1 then enter the value as 1. If 2 points is $1 then enter the value as 0.50") }}" class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="redeem_amount_per_unit_rp" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__redeem_amount_per_unit_rp'] }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Minimum order total to redeem points') }}
                <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Minimum order total for which customers can redeem points. Leave it blank if you don’t need this restriction or you need to give something for free.') }}" class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="min_order_total_for_redeem" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_order_total_for_redeem'] }}">
        </div>
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Minimum redeem point') }}
                <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Minimum redeem points that can be used per invoice. Leave it blank if you don’t need this restriction.') }}" class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="min_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__min_redeem_point'] }}">
        </div>
    </div>
    <div class="form-group row mt-2">
        <div class="col-md-4">
            <label class="fw-bold">{{ __('Maximum redeem point per order') }}
                <i data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Maximum points that can be used in one order. Leave it blank if you don’t need this restriction') }}." class="fas fa-info-circle tp"></i></label>
            <input type="number" step="any" name="max_redeem_point" class="form-control" autocomplete="off" value="{{ $generalSettings['reward_point_settings__max_redeem_point'] }}">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button reward_point_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
