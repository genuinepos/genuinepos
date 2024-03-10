<div class="table-responsive">
    <div class="table-wrap">
        <table class="table table-light table-bordered">
            <thead>
                <tr>
                    <th>
                        <div class="table-title">
                            <h4>{{ __('Choose Your Plan') }}</h4>
                        </div>
                    </th>
                    @foreach ($plans as $plan)
                        <th>
                            <div class="table-top text-center">
                                <h4>{{ $plan->name }}</h4>
                                @if ($plantype == 'month')
                                    <h5 class="price">$<span class="amount">{{ $plan->price_per_month }} </span> <span class="type text-muted">/{{ __('Monthly') }}</span>
                                @endif

                                @if ($plantype == 'year')
                                    <h5 class="price">$<span class="amount">{{ $plan->price_per_year }} </span> <span class="type text-muted">/{{ __('Yearly') }}</span>
                                @endif

                                @if ($plantype == 'lifetime')
                                    <h5 class="price">$<span class="amount">{{ $plan->lifetime_price }} </span> <span class="type text-muted">/{{ __('Lifetime') }}</span>
                                @endif

                                <div class="">
                                    <p class="p-0 m-0" style="font-size: 12px;line-height:1.2;">
                                        {{-- {{ $plan->description }} --}}
                                        {{ __('For your essential business needs.') }}
                                    </p>
                                </div>

                                @if ($plantype == 'month')
                                    <a href="{{ route('software.service.billing.upgrade.plan.cart', [$plan->id, 'month']) }}" id="link-plan" class="btn btn-primary">{{ __('Select') }}</a>
                                @endif

                                @if ($plantype == 'year')
                                    <a href="{{ route('software.service.billing.upgrade.plan.cart', [$plan->id, 'year']) }}" id="link-plan" class="btn btn-primary">{{ __('Select') }}</a>
                                @endif

                                @if ($plantype == 'lifetime')
                                    <a href="{{ route('software.service.billing.upgrade.plan.cart', [$plan->id, 'lifetime']) }}" id="link-plan" class="btn btn-primary">{{ __('Select') }}</a>
                                @endif
                            </div>
                        </th>
                    @endforeach
                    <th>
                        <div class="table-top">
                            <h4>ENTERPRISE CUSTOM PACKAGE</h4>
                            <div class="">
                                <p class="p-0 m-0" style="font-size: 12px;line-height:1.2;">
                                    {{-- {{ $plan->description }} --}}
                                    {{ __('Bill annually') }}
                                </p>
                            </div>
                            <button class="btn btn-primary">Contact</button>
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
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <th>{{ __('Business Location (Shop)') }}</th>
                    <td class="text-center">1</td>
                    <td class="text-center">1</td>
                    <td class="text-center">1</td>
                    <td class="text-center">
                        {{ __('Customization package for super shop or chain shop') }}
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Cash Counter') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">{{ isset($plan->features['cash_counter_count']) ? $plan->features['cash_counter_count'] : 0 }}/Shop</td>
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
                    <td class="text-center">Everything of business</td>
                </tr>
                <tr>
                    <th>{{ __('Warehouse') }}</th>
                    @foreach ($plans as $plan)
                        <td class="text-center">
                            @if (isset($plan->features['warehouse_count']) && $plan->features['warehouse_count'] > 0)
                                {{ $plan->features['warehouse_count'] }}
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
                                {{ $plan->features['employee_count'] }}/{{ __('Per Shop Employee') }}
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
                    <th colspan="{{ $plans->count() - 1 }}"><span>Services</span></th>
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
