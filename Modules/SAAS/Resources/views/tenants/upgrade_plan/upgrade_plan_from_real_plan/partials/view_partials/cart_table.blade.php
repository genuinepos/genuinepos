<div class="table-wrap revel-table">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Package Name') }}</th>
                    <th>{{ __('Store Quantity') }}</th>
                    <th>{{ __('Total Price') }}</th>
                    <th>{{ __('Adjustable Amount') }}</th>
                    <th>{{ __('Subtotal') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="5" class="text-center">
                        <p>{{ __('Current Plan') }} (<b>{{ $currentSubscription->plan->name }}</b>) <i class="fa-solid fa-xmark text-danger"></i></p>
                    </td>
                </tr>

                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>
                        {{ $currentSubscription->current_shop_count }}
                    </td>

                    <td>
                        <span class="price-txt">{{ $planPriceCurrency }} <span class="total-price">{{ \App\Utils\Converter::format_in_bdt($totalPrice) }}</span></span>
                    </td>

                    <td>
                        <span class="price-txt text-danger">{{ $planPriceCurrency }} <span class="adjusted-price">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</span></span>
                    </td>

                    <td><span class="price-txt">{{ $planPriceCurrency }} <span class="total-price">{{ \App\Utils\Converter::format_in_bdt($subtotal) }}</span></span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h6 class="mt-3"><a href="#" id="togglePriceAdjustmentDetails" class="text-primary">{{ __('Price Adjustment Details') }}</a></h6>
    <div class="table-responsive" style="display:none;" id="priceAdjustmentDetailsTable">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('No.') }}</th>
                    <th>{{ __('Store Name') }}</th>
                    <th>{{ __('Expire On') }}</th>
                    <th>{{ __('Adjusted Amount') }}</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $index = 1;
                @endphp

                @if ($currentSubscription->has_business == 1)
                    <tr>
                        <td>{{ $index }}</td>
                        <td>{{ $generalSettings['business_or_shop__business_name'] }}({{ location_label('business') }})</td>
                        <td class="{{ date('Y-m-d') > $currentSubscription->business_expire_date ? 'text-danger' : 'text-success' }}">
                            @if ($currentSubscription->business_price_period == 'lifetime')
                                {{ __('Lifetime') }}
                            @elseif($currentSubscription->business_price_period == 'month')
                                {{ $currentSubscription->business_expire_date . '(' . __('Monthly') . ')' }}
                            @elseif($currentSubscription->business_price_period == 'year')
                                {{ $currentSubscription->business_expire_date . '(' . __('Yearly') . ')' }}
                            @endif
                        </td>

                        <td class="text-danger">
                            <input type="hidden" name="business_adjusted_amount" value="{{ $currentSubscription->adjusted_amount }}">
                            {{ \App\Utils\Converter::format_in_bdt($currentSubscription->adjusted_amount) }}
                        </td>
                    </tr>
                @endif

                @foreach ($branches as $branch)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $branch?->shopExpireDateHistory->id }}">
                            @if ($branch?->parentBranch)
                                {{ $branch->parentBranch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                            @else
                                {{ $branch->name . '(' . $branch?->area_name . ')-' . $branch?->branch_code }}
                            @endif
                        </td>

                        <td class="{{ date('Y-m-d') > $branch->expire_date ? 'text-danger' : 'text-success' }}">
                            @if ($branch?->shopExpireDateHistory->price_period == 'lifetime')
                                {{ __('Lifetime') }}
                            @elseif($branch?->shopExpireDateHistory->price_period == 'month')
                                {{ $branch?->shopExpireDateHistory->expire_date . '(' . __('Monthly') . ')' }}
                            @elseif($branch?->shopExpireDateHistory->price_period == 'year')
                                {{ $branch?->shopExpireDateHistory->expire_date . '(' . __('Yearly') . ')' }}
                            @endif
                        </td>

                        <td class="text-danger">
                            <input type="hidden" name="shop_adjusted_amounts[]" value="{{ $branch->adjusted_amount }}">
                            {{ \App\Utils\Converter::format_in_bdt($branch->adjusted_amount) }}
                        </td>
                    </tr>
                    @php
                        $index++;
                    @endphp
                @endforeach

                @foreach ($leftBranchExpireDateHistories as $leftBranchExpireDateHistory)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $leftBranchExpireDateHistory->id }}">
                            {{ __('Store Not Yet to be created') }}
                        </td>

                        <td class="{{ date('Y-m-d') > $leftBranchExpireDateHistory->expire_date ? 'text-danger' : 'text-success' }}">
                            @if ($leftBranchExpireDateHistory->price_period == 'lifetime')
                                {{ __('Lifetime') }}
                            @elseif($leftBranchExpireDateHistory->price_period == 'month')
                                {{ $leftBranchExpireDateHistory->expire_date . '(' . __('Monthly') . ')' }}
                            @elseif($leftBranchExpireDateHistory->price_period == 'year')
                                {{ $leftBranchExpireDateHistory->expire_date . '(' . __('Yearly') . ')' }}
                            @endif
                        </td>

                        <td class="text-danger">
                            <input type="hidden" name="shop_adjusted_amounts[]" value="{{ $leftBranchExpireDateHistory->adjusted_amount }}">
                            {{ \App\Utils\Converter::format_in_bdt($leftBranchExpireDateHistory->adjusted_amount) }}
                        </td>
                    </tr>
                    @php
                        $index++;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">{{ __('Total') }} : </th>
                    <th class="text-danger">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="btn-box">
        <div>
            <label for="discount">{{ __('Discount Amount') }}</label>
            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
            <input type="text" name="discount" class="form-control form-control-sm fw-bold" id="discount" placeholder="{{ __('0.00') }}" autocomplete="off">
        </div>
    </div>
</div>

<div class="cart-total-panel">
    <h3 class="title">{{ __('Cart Total') }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-6">
                <div class="calculate-area">
                    <ul>
                        <li>
                            {{ __('Total Store Quantity') }} <span class="price-txt"><span class="">{{ $currentSubscription->current_shop_count }}</span></span>
                        </li>
                        <li>
                            {{ __('Net Total') }} <span class="price-txt">{{ $planPriceCurrency }} <span class="span_net_total">{{ \App\Utils\Converter::format_in_bdt($totalPrice) }}</span></span>
                            <input type="hidden" name="net_total" id="net_total" value="{{ round($totalPrice, 2) }}">
                        </li>
                        <li>
                            {{ __('Adjusted Amount') }}
                            <span class="price-txt text-danger">
                                {{ $planPriceCurrency }} <span class="sub-total">{{ \App\Utils\Converter::format_in_bdt($totalAdjustableAmount) }}</span>
                            </span>
                            <input type="hidden" name="total_adjusted_amount" id="total_adjusted_amount" value="{{ round($totalAdjustableAmount, 2) }}">
                        </li>
                        <li>
                            {{ __('Tax') }}
                            <span class="price-txt" id="tax">
                                <span class="text-success">{{ __('Free') }}</span>
                            </span>
                        </li>
                        <li>
                            {{ __('Discount') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} (<span class="span_discount text-danger">0%=0.00</span>)
                            </span>
                        </li>
                        <li class="total-price-wrap">
                            {{ __('Total Payable') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_total_payable text-success">{{ \App\Utils\Converter::format_in_bdt($subtotal) }}</span>
                                <input type="hidden" name="total_payable" id="total_payable" value="{{ round($subtotal, 0) }}">
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cart-total-panel payment-section">
                    <input type="hidden" name="payment_status" value="1">
                    <div class="panel-body">
                        <div class="row gy-4">
                            <div class="col-12">
                                <div class="form-col-5">
                                    <label for="payment_date">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                                    <input required name="payment_date" id="payment_date" class="form-control form-control-sm" value="{{ date('d-m-Y') }}" autocomplete="off">
                                </div>

                                <div class="form-col-5 mt-2">
                                    <label for="payment_method_name">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                                    <select required name="payment_method_name" id="payment_method_name" class="form-control form-control-sm select wide">
                                        <option value="">{{ __('Select Payment Method') }}</option>
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Card">{{ __('Card') }}</option>
                                        <option value="Bkash">{{ __('Bkash') }}</option>
                                        <option value="Recket">{{ __('Recket') }}</option>
                                        <option value="Naged">{{ __('Naged') }}</option>
                                    </select>
                                </div>

                                <div class="form-col-5 mt-2">
                                    <label for="payment_method_name">{{ __('Payment Transaction ID') }}</label>
                                    <input name="payment_trans_id" id="payment_trans_id" class="form-control form-control-sm select wide">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="def-btn palce-order tab-next-btn btn-success text-center float-end" id="submit_button">
                    {{ __('Confirm') }}
                </button>

                <button type="button" class="def-btn palce-order tab-next-btn btn-success d-none float-end" id="loading_button">
                    {{ __('Loading...') }}
                </button>
            </div>
        </div>
    </div>
</div>
