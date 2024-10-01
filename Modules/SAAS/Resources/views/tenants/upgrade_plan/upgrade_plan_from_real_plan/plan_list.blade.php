<x-saas::admin-layout title="Upgradeable Plan List">
    @push('css')
        <style>
            .main-content .digi-dataTable thead th {
                font-weight: 400;
                padding: 4px 22px 5px 4px;
                background-position-x: calc(100% - 10px);
                background-size: 9px;
                font-size: 11px;
            }

            .main-content .digi-dataTable tr td {
                vertical-align: middle;
                padding: 6px 5px 4px 3px;
                font-size: 11px;
            }

            table.dataTable tfoot th,
            table.dataTable tfoot td {
                padding: 4px 17px 4px 4px;
                border-top: 1px solid #111;
                font-size: 11px;
            }

            table td .btn-sm {
                padding: 1px 6px !important;
                font-size: 11px !important;
            }

            .dropdown-menu {
                padding: 2px 0;
            }

            .dropdown-menu .dropdown-item {
                font-size: 11px;
                padding: 2px 7px;
            }
        </style>
    @endpush

    @php
        $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
    @endphp
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Upgradeable Plan List') }} |
                <span class="fw-bold">{{ __('Customer') }} :</span> {{ $tenant?->user?->name }} | <span class="fw-bold">{{ __('Business') }} :</span> {{ $tenant->name }} | <span class="fw-bold">{{ __('Current Plan') }} :</span> <span class="fw-bold text-danger">{{ $tenant?->user?->userSubscription?->plan?->name }}</span>
            </h5>
        </div>
        <div class="panel-body">
            <div class="row">
                @foreach ($plans as $plan)
                    <div class="col-md-3">
                        <ul class="list-group text-center">
                            <li class="list-group-item text-white fw-bold active" aria-current="true">
                                {{ $plan->name }} <br>
                                @if ($plan->plan_type == 2)
                                    <span class="badge badge-sm bg-warning text-white">{{ __('Custom') }}</span>
                                @else
                                    <span class="badge badge-sm bg-primary text-white">{{ __('Fixed') }}</span>
                                @endif
                            </li>
                            {{-- <li class="list-group-item text-white fw-bold active" aria-current="true">
                                <p class="text-success p-0 m-0">Custom</p>
                            </li> --}}
                            <li class="list-group-item">
                                {{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_month)) }} </span> <span class="type text-muted">/{{ __('Monthly') }}</span>
                            </li>

                            <li class="list-group-item">
                                {{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->price_per_year)) }} </span> <span class="type text-muted">/{{ __('Yearly') }}
                            </li>

                            <li class="list-group-item">
                                {{ $planPriceCurrency }} <span class="amount">{{ \App\Utils\Converter::format_in_bdt(\Modules\SAAS\Utils\PlanPriceIfLocationIsBd::amount($plan->lifetime_price)) }} </span> <span class="type text-muted">/{{ __('Lifetime') }}
                            </li>

                            {{-- <li class="list-group-item"><a href="#" class="btn btn-sm btn-primary">{{ __('Select') }}</a></li> --}}

                            <li class="list-group-item">
                                @if ($plan->id == $tenant?->user?->userSubscription?->plan?->id)
                                    <button href="#" id="link-plan" class="btn btn-primary" disabled>{{ __('Current Plan') }}</button>
                                @else
                                    @if ($plan->price_per_month < $tenant?->user?->userSubscription?->plan?->price_per_month && $plan->price_per_year < $tenant?->user?->userSubscription?->plan?->price_per_year)
                                        <button href="#" id="link-plan" class="btn btn-primary" disabled>{{ __('Select') }}</button>
                                    @else
                                        <a href="{{ route('saas.tenants.upgrade.plan.cart', ['tenantId' => $tenant->id, 'planId' => $plan->id]) }}" id="link-plan" class="btn btn-primary">{{ __('Select') }}</a>
                                    @endif
                                @endif
                            </li>
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('js')
    @endpush
</x-saas::admin-layout>
