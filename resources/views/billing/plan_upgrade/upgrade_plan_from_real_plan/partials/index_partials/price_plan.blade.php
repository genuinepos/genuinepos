<div class="table-responsive">
    <div class="table-wrap">
        <table class="table table-light table-bordered">
            <thead>
                <tr>
                    <th>
                        <div class="table-title">
                            <h6>{{ __('Choose Your Plan') }}</h6>
                        </div>
                    </th>
                    @php
                        $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
                    @endphp
                    @foreach ($plans as $plan)
                        <th>
                            <div class="table-top text-center">
                                <h4>{{ $plan->name }}</h4>
                                <h6 class="price">{{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }} </span> <span class="type text-muted">/{{ __('Monthly') }}</span>

                                <h6 class="price">{{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year)) }} </span> <span class="type text-muted">/{{ __('Yearly') }}</span>

                                <h6 class="price">{{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price)) }} </span> <span class="type text-muted">/{{ __('Lifetime') }}</span>

                                <div class="">
                                    <p class="p-0 m-0" style="font-size: 12px;line-height:1.2;">
                                        {{-- {{ $plan->description }} --}}
                                        {{ __('For your essential business needs.') }}
                                    </p>
                                </div>

                                @if ($plan->id == $generalSettings['subscription']->plan_id)

                                    <button href="#" id="link-plan" class="btn btn-primary" disabled>{{ __('Current Plan') }}</button>
                                @else

                                    @if ($plan->price_per_month < $generalSettings['subscription']->price_per_month && $plan->price_per_year < $generalSettings['subscription']->price_per_year)

                                        <button href="#" id="link-plan" class="btn btn-primary" disabled>{{ __('Select') }}</button>
                                    @else

                                        <a href="{{ route('software.service.billing.upgrade.plan.cart', [$plan->id]) }}" id="link-plan" class="btn btn-primary">{{ __('Select') }}</a>
                                    @endif
                                @endif
                            </div>
                        </th>
                    @endforeach
                    <th>
                        <div class="table-top  text-center">
                            <h4>{{ __("ENTERPRISE CUSTOM PACKAGE") }}</h4>
                            <div class="">
                                <p class="p-0 m-0" style="font-size: 12px;line-height:1.2;">
                                    {{-- {{ $plan->description }} --}}
                                    {{ __('Bill annually') }}
                                </p>
                            </div>
                            <button class="btn btn-primary">{{ __("Contact") }}</button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-secondary">
                    <th><span>{{ __('Features') }}</span></th>
                    @foreach ($plans as $plan)
                        <td></td>
                    @endforeach
                    <td></td>
                </tr>
                <tr>
                    <th>{{ __('Multi store management') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Business Location (Store)') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center fw-bold">{{ __("1") }}</td>
                    @endforeach
                    <td class="text-center">
                        {{ __('Customization package for super shop or chain shop') }}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Cash Counter') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            <span class="fw-bold">{{ isset($plan->features['cash_counter_count']) ? $plan->features['cash_counter_count'] : 0 }}</span>/{{ __("Per Store") }}
                        </td>
                    @endforeach
                    <td class="text-center">{{ __('Everything of business') }}</td>
                </tr>
                <tr>
                    <th>{{ __('Inventory') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if ($plan->features['inventory'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Sales') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['sales']) && $plan->features['sales'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Purchase') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['purchase']) && $plan->features['purchase'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Customer') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center">{{ __('Everything of business') }}</td>
                </tr>
                <tr>
                    <th>{{ __('Supplier') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center">{{ __("Everything of business") }}</td>
                </tr>
                <tr>
                    <th>{{ __('Warehouse') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['warehouse_count']) && $plan->features['warehouse_count'] > 0)
                                <span class="fw-bold">{{ $plan->features['warehouse_count'] }}</span>/{{ __("Per Store") }}
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Accounting') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['accounting']) && $plan->features['accounting'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Rhythm Point') }}</th>
                    <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Stock Adjustment') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['stock_adjustments']) && $plan->features['stock_adjustments'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('HRM') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['employee_count']) && $plan->features['employee_count'] > 0)
                                {{ $plan->features['employee_count'] }}/{{ __('Per Store Employee') }}
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Retail POS') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('eCommerce') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['ecommerce']) && $plan->features['ecommerce'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Multi store availability') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Transfer stock') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['transfer_stocks']) && $plan->features['transfer_stocks'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Manufacturing') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['manufacturing']) && $plan->features['manufacturing'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Task Management') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['task_management']) && $plan->features['task_management'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Communication') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['communication']) && $plan->features['communication'] == 1)
                                <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                            @else
                                <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                            @endif
                        </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('User activity log') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Bar-code generator') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr class="table-secondary">
                    <th colspan="{{ $plans->count() - 1 }}"><span>{{ __("Services") }}</span></th>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{ __('24/7 customer support') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Documents and tutorial') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Dedicated support') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- <td>
        {{ $plan->name }} -
        @foreach ($plan->features as $fKey => $feature)
        <br>
        {{ str($fKey)->headline() }} - {{ $feature }}
        @endforeach
    </td> --}}
</div>
