<x-saas::admin-layout title="Upgrade Plan">
    @push('css')
        <style>
            .tab-section .tab-nav .single-nav {
                height: 35px;
                font-size: 15px;
            }

            .quantity .quantity-nav .quantity-button {
                width: 35px;
                height: 28px;
                line-height: 29px;
            }

            .quantity {
                width: 140px;
                height: 28px;
            }

            .revel-table .table-responsive th {
                padding: 8px 30px;
            }

            .revel-table {
                margin-bottom: 11px;
            }

            .revel-table .table-responsive tr:last-child td {
                padding-bottom: 20px;
            }

            .revel-table .table-responsive tr:first-child td {
                padding-top: 20px;
            }

            .tab-section .tab-contents .cart-total-panel .title {
                font-size: 16px;
                height: 40px;
                line-height: 40px;
                padding: 0 20px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body .calculate-area ul li:nth-child(2) {
                margin-bottom: 16px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body .calculate-area ul li {
                font-size: 12px;
                margin-bottom: 15px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body {
                padding: 20px;
            }

            .cart-coupon-form input {
                height: 40px;
            }

            .def-btn {
                height: 40px;
                line-height: 40px;
                padding: 0 30px;
                font-size: 13px;
            }

            .tab-section .tab-contents .tab-next-btn {
                font-size: 13px;
                text-align: center;
            }

            .tab-section .tab-contents .billing-details .form-row {
                gap: 10px 20px;
            }

            .tab-section .tab-contents .billing-details .form-row .form-col-5 label,
            .tab-section .tab-contents .billing-details .form-row .form-col-10 label {
                font-size: 13px;
            }

            .tab-section .tab-contents .billing-details .form-row .form-control {
                font-size: 14px;
                height: 35px;
                line-height: 33px;
                padding: 0 15px;
            }

            .domain-field span.txt {
                font-size: 17px;
            }

            .tab-section .tab-contents .billing-details .title {
                font-size: 16px;
            }

            .plan-select {
                max-width: 172px;
            }

            .table-responsive {
                min-height: 200px !important;
            }

            .tab-section .tab-contents .btn-box {
                margin-top: 5px;
                margin-bottom: 6px;
            }

            .tab-section .tab-contents .cart-total-panel .title {
                font-size: 14px;
                height: 29px;
                line-height: 29px;
                padding: 0 18px;
            }

            .tab-section .tab-contents .cart-total-panel .panel-body {
                padding: 14px;
            }

            .tab-section .tab-contents .cart-total-panel {
                margin-bottom: 18px;
            }
        </style>
        <link href="{{ asset('assets/plugins/custom/toastrjs/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush

    @php
        $planPriceCurrency = \Modules\SAAS\Utils\PlanPriceCurrencySymbol::currencySymbol();
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Upgrade Plan') }} |
                        <span class="fw-bold">{{ __("Customer") }} :</span> {{ $tenant?->user->name }} | <span class="fw-bold">{{ __("Business") }} :</span> {{ $tenant->name }} | <span class="fw-bold">{{ __("Current Plan") }} :</span> <span class="fw-bold text-danger">Trial</span>
                    </h5>
                </div>
                <div class="panel-body">
                    <div class="tab-section py-120">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="tab-contents">
                                        <form id="upgrade_plan_form" action="{{ route('saas.tenants.upgrade.plan.confirm', $tenantId) }}" method="POST">
                                            @csrf
                                            @include('saas::tenants.upgrade_plan.upgrade_plan_from_trial.partials.view_partials.cart_table')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        @include('saas::tenants.upgrade_plan.upgrade_plan_from_trial.partials.js_partial.js')
    @endpush
</x-saas::admin-layout>
