<div class="row g-lg-3 g-1 payment_method_settings tab_contant">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form id="payment_method_settings_form" action="{{ route('payment.methods.settings.add.or.update') }}" method="POST">
                    @csrf
                    <p class="m-0 p-0 py-1"><b> {{ __('Settings For') }} : </b>
                        @if (auth()?->user()?->branch?->parent_branch_id)

                            {{ auth()?->user()?->branch?->parentBranch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                        @else
                            @if (auth()?->user()?->branch)
                                {{ auth()?->user()?->branch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                            @else
                                {{ $generalSettings['business__business_name'] }}
                            @endif
                        @endif
                    </p>

                    <div class="table-responsive">
                        <table class="modal-table table table-sm">
                            <thead>
                                <th class="text-start">{{ __('S/L') }}</th>
                                <th class="text-start">{{ __('Payment Method') }}</th>
                                <th class="text-start">{{ __('Default Account') }}</th>
                            </thead>
                            <tbody id="payment_method_settings_body">
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 d-flex justify-content-end pb-2">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button payment_method_settings_loading_btn d-hide"><i class="fas fa-spinner"></i><span>{{ __("Loading") }}...</span></button>
                                <button type="submit" id="save_payment_settings_save_changes" class="btn btn-success payment_method_settings_submit_button">{{ __("Save Changes") }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
