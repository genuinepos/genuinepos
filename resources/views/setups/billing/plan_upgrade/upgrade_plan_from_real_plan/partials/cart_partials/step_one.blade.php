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
                    <th>{{ __('Shop/Business Name') }}</th>
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
                        <td>{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</td>
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
                            {{ __('Shop Not Yet to be created') }}
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

    <div class="btn-box" id="coupon_success_msg" style="display:none;">
        <p class="bg-success d-block p-3 m-0"><span class="text-white">{{ __('Applied Coupon is') }} : <span id="applied_coupon_code"></span></span> <a href="#" class="btn btn-sm btn-danger" id="remove_applied_coupon">X</a></p>
    </div>

    <div class="btn-box">
        <div class="cart-coupon-form" id="coupon_code_applying_area">
            <input type="text" name="coupon_code" id="coupon_code" placeholder="{{ __('Enter Your Coupon Code') }}">
            <input type="hidden" name="coupon_id" id="coupon_id" value="">
            <button type="button" class="def-btn coupon-apply-btn" id="applyCouponBtn">{{ __('Apply Coupon') }}</button>
            <button type="button" style="display: none;" class="def-btn coupon-apply-btn" id="applyCouponLodingBtn">{{ __('Loading...') }}</button>
        </div>
    </div>
</div>

<div class="cart-total-panel">
    <h3 class="title">{{ __('Cart Total') }}</h3>
    <div class="panel-body">
        <div class="row gy-5">
            <div class="col-12">
                <div class="calculate-area">
                    <ul>
                        <li>
                            {{ __('Total Store Quantity') }} <span class="price-txt"><span class="">{{ $currentSubscription->current_shop_count }}</span></span>
                        </li>
                        <li>
                            {{ __('Net Total') }} <span class="price-txt">{{ $planPriceCurrency }} <span class="sub-total">{{ \App\Utils\Converter::format_in_bdt($totalPrice) }}</span></span>
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
                                {{ $planPriceCurrency }} <span class="span_discount">0.00</span>
                            </span>
                            <input type="hidden" name="discount_percent" id="discount_percent" value="0">
                            <input type="hidden" name="discount" id="discount" value="0">
                        </li>
                        <li class="total-price-wrap">
                            {{ __('Total Payable') }}
                            <span class="price-txt">
                                {{ $planPriceCurrency }} <span class="span_total_payable">{{ \App\Utils\Converter::format_in_bdt($subtotal) }}</span>
                                <input type="hidden" name="total_payable" id="total_payable" value="{{ round($subtotal, 2) }}">
                            </span>
                        </li>
                    </ul>
                    <a class="single-nav def-btn tab-next-btn" data-tab="stepTwoTab">{{ __('Next Step') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
