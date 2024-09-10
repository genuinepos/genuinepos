@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
    </style>
@endpush
@section('title', 'Manage Customer - ')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .contract_info_area ul li strong {
                color: #495677
            }

            .account_summary_area .heading h5 {
                background: #0F3057;
                color: white
            }

            .contract_info_area ul li strong i {
                color: #495b77;
                font-size: 13px;
            }
        </style>
    @endpush
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Manage Customer') }} - (<strong>{{ $contact->name }}</strong>){{ $contact?->account->id }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                    </div>

                    <div class="tab_list_area">
                        <div class="btn-group">
                            @if (auth()->user()->can('customer_ledger'))
                                <a id="tab_btn" data-show="ledger" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                    <i class="fas fa-scroll"></i> {{ __('Ledger') }}
                                </a>
                            @endif

                            <a id="tab_btn" data-show="contract_info_area" class="btn btn-sm btn-primary tab_btn" href="#">
                                <i class="fas fa-info-circle"></i> {{ __('Contract Info') }}
                            </a>

                            @if ((isset($generalSettings['subscription']->features['services']) && $generalSettings['subscription']->features['services'] == \App\Enums\BooleanType::True->value) || $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                                @if (auth()->user()->can('sales_index') || auth()->user()->can('service_invoices_index'))
                                    <a id="tab_btn" data-show="sale" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> {{ __('Sales') }}
                                    </a>
                                @endif
                            @endif

                            @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                                @if (auth()->user()->can('sales_orders_index'))
                                    <a id="tab_btn" data-show="sales_order" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-shopping-bag"></i> {{ __('Sales Orders') }}
                                    </a>
                                @endif
                            @endif

                            @if (auth()->user()->can('purchase_all'))
                                <a id="tab_btn" data-show="purchases" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fas fa-shopping-bag"></i> {{ __('Purchases') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('purchase_order_index'))
                                <a id="tab_btn" data-show="purchase_orders" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="fas fa-shopping-bag"></i> {{ __('Purchases Orders') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('receipts_index'))
                                <a id="tab_btn" data-show="receipts" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="far fa-money-bill-alt"></i> {{ __('Receipts') }}
                                </a>
                            @endif

                            @if (auth()->user()->can('payments_index'))
                                <a id="tab_btn" data-show="payments" class="btn btn-sm btn-primary tab_btn" href="#">
                                    <i class="far fa-money-bill-alt"></i> {{ __('Payments') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if (auth()->user()->can('customer_ledger'))
                        @include('contacts.manage_customers.partials.tab_content_partials.ledger')
                    @endif

                    @include('contacts.manage_customers.partials.tab_content_partials.contact_info')

                    @if ((isset($generalSettings['subscription']->features['services']) && $generalSettings['subscription']->features['services'] == \App\Enums\BooleanType::True->value) || $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                        @if (auth()->user()->can('sales_index') || auth()->user()->can('service_invoices_index'))
                            @include('contacts.manage_customers.partials.tab_content_partials.sales')
                        @endif
                    @endif

                    @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
                        @if (auth()->user()->can('sales_orders_index'))
                            @include('contacts.manage_customers.partials.tab_content_partials.sales_order')
                        @endif
                    @endif

                    @if (auth()->user()->can('purchase_all'))
                        @include('contacts.manage_customers.partials.tab_content_partials.purchases')
                    @endif

                    @if (auth()->user()->can('purchase_order_index'))
                        @include('contacts.manage_customers.partials.tab_content_partials.purchase_orders')
                    @endif

                    @if (auth()->user()->can('receipts_index'))
                        @include('contacts.manage_customers.partials.tab_content_partials.receipts')
                    @endif

                    @if (auth()->user()->can('payments_index'))
                        @include('contacts.manage_customers.partials.tab_content_partials.payments')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    @if (auth()->user()->can('shipment_access') && $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        <!-- Edit Shipping modal -->
        <div class="modal fade" id="editShipmentDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    @if (auth()->user()->can('receipts_index'))
        <div class="modal fade" id="addOrEditReceiptModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    @if (auth()->user()->can('payments_index'))
        <div class="modal fade" id="addOrEditPaymentModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    @endif

    <div id="details"></div>
    <div id="extra_details"></div>
@endsection
@push('scripts')
    @if (auth()->user()->can('customer_ledger'))
        @include('contacts.manage_customers.js_partials.tab_content_js_partials.ledger_js')
    @endif

    @if ((isset($generalSettings['subscription']->features['services']) && $generalSettings['subscription']->features['services'] == \App\Enums\BooleanType::True->value) || $generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('sales_index') || auth()->user()->can('service_invoices_index'))
            @include('contacts.manage_customers.js_partials.tab_content_js_partials.sales_js')
        @endif
    @endif

    @if ($generalSettings['subscription']->features['sales'] == \App\Enums\BooleanType::True->value)
        @if (auth()->user()->can('sales_orders_index'))
            @include('contacts.manage_customers.js_partials.tab_content_js_partials.sales_order_js')
        @endif
    @endif

    @if (auth()->user()->can('purchase_all'))
        @include('contacts.manage_customers.js_partials.tab_content_js_partials.purchase_js')
    @endif

    @if (auth()->user()->can('purchase_order_index'))
        @include('contacts.manage_customers.js_partials.tab_content_js_partials.purchase_orders_js')
    @endif

    @if (auth()->user()->can('receipts_index'))
        @include('contacts.manage_customers.js_partials.tab_content_js_partials.receipts_js')
    @endif

    @if (auth()->user()->can('payments_index'))
        @include('contacts.manage_customers.js_partials.tab_content_js_partials.payments_js')
    @endif

    @include('contacts.manage_customers.js_partials.manage_js')
@endpush
