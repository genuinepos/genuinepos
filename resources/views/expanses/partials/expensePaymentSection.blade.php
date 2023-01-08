<section>
    <div class="form_element rounded mt-0 mb-3">
        <div class="element-body">
            <div class="row gx-2 mt-1">
                <div class="col-md-6">
                    <div class="input-group">
                        <label class=" col-4">
                            <b>{{ __('Paying') }} :
                                ({{ $generalSettings['business__currency'] }})
                            </b>
                        </label>

                        <div class="col-8">
                            <input required type="number" step="any" name="paying_amount" class="form-control" id="paying_amount" autocomplete="off">
                            <span class="error error_paying_amount"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group mt-1">
                        <label class="col-4"><b>{{ __('Pay Method') }} :</b></label>
                        <div class="col-8">
                            <select name="payment_method_id" class="form-control" id="payment_method_id">
                                @foreach ($methods as $method)
                                    <option
                                        data-account_id="{{ $method->methodAccount ? $method->methodAccount->account_id : '' }}"
                                        value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gx-2 mt-1">
                <div class="col-md-6">
                    <div class="input-group">
                        <label class="col-4"><b>@lang('menu.credit_account') :</b></label>
                        <div class="col-8">
                            <select required name="account_id" class="form-control" id="account_id">
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        @php
                                            $accountType = $account->account_type == 1 ? ' (Cash-In-Hand)' : '(Bank A/C)';
                                            $bank = $account->bank ? ', BK : '.$account->bank : '';
                                            $ac_no = $account->account_number ? ', A/c No : '.$account->account_number : '';
                                            $balance = ', BL : '.$account->balance;
                                        @endphp
                                        {{ $account->name.$accountType.$bank.$ac_no.$balance }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error error_account_id"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group">
                        <label class="col-4"><b>@lang('menu.total_due') :</b> </label>
                        <div class="col-8">
                            <input readonly name="total_due" type="number" step="any" id="total_due" class="form-control text-danger" value="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-md-12">
                    <div class="input-group">
                        <label class="col-lg-2 col-4"><b>@lang('menu.payment_note') :</b></label>

                        <div class="col-lg-10 col-8">
                            <input type="text" name="payment_note" class="form-control form-control-sm" id="payment_note" placeholder="@lang('menu.payment_note')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="submit-area d-flex justify-content-end mt-3">
        <div class="btn-loading">
            <button type="button" class="btn loading_button d-hide">
                <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
            </button>

            <button data-action="save" id="save" class="btn btn-sm btn-success submit_button">
                @lang('menu.save')
            </button>

            <button data-action="sale_and_print" id="save_and_print" class="btn btn-sm btn-success submit_button">
                @lang('menu.save_print')
            </button>
        </div>
    </div>
</section>
