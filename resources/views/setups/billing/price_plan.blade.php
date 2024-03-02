<div class="table-responsive">
    <div class="table-wrap">
        <table class="table table-light table-bordered">
            <thead>
                <tr>
                    <th>
                        <div class="table-title">
                            <h4>Choose Your Plan</h4>
                        </div>
                    </th>
                    @foreach ($plans as $plan)
                    <th>
                        <div class="table-top">
                            <h3>{{ $plan->name }}</h3>
                            @if($plantype == 'monthly')
                            <h2 class="price">$<span class="amount">{{ $plan->price_per_month }} </span> <span class="type">Monthly</span>
                            @endif
                            @if($plantype == 'yearly')
                            <h2 class="price">$<span class="amount">{{ $plan->price_per_year }} </span> <span class="type">Monthly</span>
                            @endif
                            @if($plantype == 'lifetime')
                            <h2 class="price">$<span class="amount">{{ $plan->lifetime_price }} </span> <span class="type">Monthly</span>
                            @endif
                            <div class="">For your essential business needs.</div>
                            <a href="{{ route('software.service.billing.cart.for.upgrade.plan', $plan->id) }}" class="btn btn-primary">Upgrade</a>
                        </div>
                    </th>
                    @endforeach
                    <th>
                        <div class="table-top">
                            <h3>ENTERPRISE CUSTOM</h3>
                            <h2 class="price">PACKAGE</h2>
                            <div>Bill annually</div>
                            <button class="btn btn-primary">Contact</button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-secondary">
                    <td colspan="{{ $plans->count() - 1 }}"><span>Products</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Multipool store management</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Business Location (Shop)</td>
                    <td class="text-center">1</td>
                    <td class="text-center">1</td>
                    <td class="text-center">1</td>
                    <td class="text-center">
                        Customization package for super shop or chain shop
                    </td>
                </tr>
                <tr>
                    <td>Cash Counter</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">{{ isset($plan->features['cash_counter_count']) ? $plan->features['cash_counter_count'] : 0 }}/Shop</td>
                    @endforeach
                    <td class="text-center">Everything of business</td>
                </tr>
                <tr>
                    <td>Inventory</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['inventory']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Sales</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['sales']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Purchase</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['purchase']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center">Everything of business</td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center">Everything of business</td>
                </tr>
                <tr>
                    <td>Warehouse</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['warehouse_count']) && $plan->features['warehouse_count'] > 0)
                        {{ $plan->features['warehouse_count'] }}
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Accounting</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['accounting']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Rhythm Point</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Stock Adjustment</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['stock_adjustments']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>HRM</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['employee_count']) && $plan->features['employee_count'] > 0)
                        {{ $plan->features['employee_count'] }}/shop
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Retail POS</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>eCommerce</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['ecommerce']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Multi store availability</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Transfer stock</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['transfer_stocks']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Manufacturing</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['manufacturing']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Task Management</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['task_management']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Communication</td>
                    @foreach ($plans as $plan)
                    <td class="text-center">
                        @if(isset($plan->features['communication']))
                        <span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span>
                        @else
                        <span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span>
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>User activity log</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Bar-code generator</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr class="table-secondary">
                    <td colspan="{{ $plans->count() - 1 }}"><span>Services</span></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>24/7 customer support</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Documents and tutorial</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
                </tr>
                <tr>
                    <td>Dedicated support</td>
                    <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-minus text-secondary fa-2x"></i></span></td>
                    <td class="text-center"><span class="icon check"><i class="far fa-check-circle text-success fa-2x"></i></span></td>
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
