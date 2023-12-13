<div id="primary_nav" class="g_blue toggle-leftbar">
    <div class="first__left">
        <div class="main__nav">
            <ul id="" class="float-right">
                <li data-menu="dashboardmenu" class="">
                    <a href="{{ route('dashboard.index') }}" class="">
                        <img src="{{ asset('backend/asset/img/icon/pie-chart.svg') }}" alt="">
                        <p class="title">{{ __('Dashboard') }}</p>
                    </a>
                </li>

                <li data-menu="store" class="{{ request()->is('setups/branches*') ? 'menu_active' : '' }}">
                    <a href="{{ route('branches.index') }}" class=""><img src="{{ asset('backend/asset/img/icon/shop.svg') }}">
                        <p class="title">{{ __('Store') }}</p>
                    </a>
                </li>

                @if ($generalSettings['modules__contacts'] == '1')
                    @if (auth()->user()->can('supplier_all') ||
                            auth()->user()->can('supplier_add') ||
                            auth()->user()->can('supplier_import') ||
                            auth()->user()->can('customer_all') ||
                            auth()->user()->can('customer_add') ||
                            auth()->user()->can('customer_import') ||
                            auth()->user()->can('customer_group') ||
                            auth()->user()->can('supplier_report') ||
                            auth()->user()->can('customer_report'))
                        <li data-menu="contact" class="{{ request()->is('contacts*') ? 'menu_active' : '' }}">
                            <a href="#" class=""><img src="{{ asset('backend/asset/img/icon/agenda.svg') }}">
                                <p class="title">{{ __('Contacts') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (
                    auth()->user()->can('product_all') ||
                    auth()->user()->can('product_add') ||
                    auth()->user()->can('product_import') ||
                    auth()->user()->can('product_category_index') ||
                    auth()->user()->can('product_brand_index') ||
                    auth()->user()->can('product_unit_index') ||
                    auth()->user()->can('product_variant_index') ||
                    auth()->user()->can('product_warranty_index') ||
                    auth()->user()->can('selling_price_group_index') ||
                    auth()->user()->can('generate_barcode') ||
                    auth()->user()->can('product_settings') ||
                    auth()->user()->can('stock_report') ||
                    auth()->user()->can('stock_in_out_report')
                )
                    <li data-menu="product" class="{{ request()->is('product*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/package.svg') }}" alt="">
                            <p class="title">{{ __('Product') }}</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__purchases'] == '1')
                    @if (auth()->user()->can('purchase_all') ||
                            auth()->user()->can('purchased_product_list') ||
                            auth()->user()->can('purchase_add') ||
                            auth()->user()->can('purchase_settings') ||
                            auth()->user()->can('purchase_order_index') ||
                            auth()->user()->can('purchase_order_add') ||
                            auth()->user()->can('purchase_return_index') ||
                            auth()->user()->can('purchase_return_add') ||
                            auth()->user()->can('purchase_report') ||
                            auth()->user()->can('purchase_order_report') ||
                            auth()->user()->can('purchase_ordered_product_report') ||
                            auth()->user()->can('purchase_return_report') ||
                            auth()->user()->can('purchase_returned_product_report') ||
                            auth()->user()->can('product_purchase_report') ||
                            auth()->user()->can('purchase_sale_report') ||
                            auth()->user()->can('product_purchase_report') ||
                            auth()->user()->can('purchase_payment_report'))
                        <li data-menu="purchases" class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                            <a href="#" class="">
                                <img src="{{ asset('backend/asset/img/icon/bill.svg') }}">
                                <p class="title">{{ __('Purchases') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (auth()->user()->can('pos_all') ||
                        auth()->user()->can('pos_add') ||
                        auth()->user()->can('create_add_sale') ||
                        auth()->user()->can('view_add_sale') ||
                        auth()->user()->can('sale_draft') ||
                        auth()->user()->can('sale_quotation') ||
                        auth()->user()->can('shipment_access') ||
                        auth()->user()->can('pos_sale_settings') ||
                        auth()->user()->can('add_sale_settings') ||
                        auth()->user()->can('discounts') ||
                        auth()->user()->can('sales_order_to_invoice') ||
                        auth()->user()->can('sales_report') ||
                        auth()->user()->can('sales_return_report') ||
                        auth()->user()->can('sold_product_report') ||
                        auth()->user()->can('sales_order_report') ||
                        auth()->user()->can('sales_ordered_products_report') ||
                        auth()->user()->can('received_against_sales_report') ||
                        auth()->user()->can('sales_returned_products_report') ||
                        auth()->user()->can('cash_register_report'))
                    <li data-menu="sales" class="{{ request()->is('sales*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/shopping-bag.svg') }}">
                            <p class="title">{{ __('Sales') }}</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__transfer_stock'] == '1')

                    @if (auth()->user()->can('transfer_stock_index') ||
                            auth()->user()->can('transfer_stock_create') ||
                            auth()->user()->can('transfer_stock_receive_from_warehouse') ||
                            auth()->user()->can('transfer_stock_receive_from_branch'))
                        <li data-menu="transfer" class="{{ request()->is('transfer-stocks*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/transfer.svg') }}">
                                <p class="title">{{ __('Transfer') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['modules__stock_adjustments'] == '1')

                    @if (auth()->user()->can('stock_adjustment_all') ||
                            auth()->user()->can('stock_adjustment_add') ||
                            auth()->user()->can('stock_adjustment_report'))
                        <li data-menu="adjustment" class="{{ request()->is('stock/adjustments*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/slider-tool.svg') }}">
                                <p class="title">{{ __('Adjustments') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['modules__accounting'] == '1')

                    @if (auth()->user()->can('banks_index') ||
                            auth()->user()->can('account_groups_index') ||
                            auth()->user()->can('accounts_index') ||
                            auth()->user()->can('capital_accounts_index') ||
                            auth()->user()->can('duties_and_taxes_index') ||
                            auth()->user()->can('receipts_index') ||
                            auth()->user()->can('payments_index') ||
                            auth()->user()->can('expenses_index') ||
                            auth()->user()->can('contras_index') ||
                            auth()->user()->can('profit_loss') ||
                            auth()->user()->can('financial_report') ||
                            auth()->user()->can('profit_loss_account') ||
                            auth()->user()->can('balance_sheet') ||
                            auth()->user()->can('trial_balance') ||
                            auth()->user()->can('cash_flow'))
                        <li data-menu="accounting" class="{{ request()->is('accounting*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/accounting.svg') }}">
                                <p class="title">{{ __('Accounting') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (auth()->user()->can('user_view') ||
                        auth()->user()->can('user_add') ||
                        auth()->user()->can('role_view') ||
                        auth()->user()->can('role_add'))
                    <li data-menu="users" class="{{ request()->is('users*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/team.svg') }}">
                            <p class="title">{{ __('Users') }}</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['addons__hrm'])
                    @if (auth()->user()->can('hrm_dashboard') ||
                            auth()->user()->can('leave_type') ||
                            auth()->user()->can('leave_assign') ||
                            auth()->user()->can('shift') ||
                            auth()->user()->can('attendance') ||
                            auth()->user()->can('view_allowance_and_deduction') ||
                            auth()->user()->can('payroll') ||
                            auth()->user()->can('department') ||
                            auth()->user()->can('designation') ||
                            auth()->user()->can('payroll_report') ||
                            auth()->user()->can('payroll_payment_report') ||
                            auth()->user()->can('attendance_report'))
                        <li data-menu="hrm" class="{{ request()->is('hrm*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/human-resources.svg') }}">
                                <p class="title">{{ __('HRM') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['addons__manufacturing'] == 1)
                    @if (auth()->user()->can('process_view') ||
                            auth()->user()->can('production_view') ||
                            auth()->user()->can('manufacturing_settings') ||
                            auth()->user()->can('manufacturing_report'))
                        <li data-menu="manufacture" class="{{ request()->is('manufacturing*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/conveyor.svg') }}">
                                <p class="title">{{ __('Manufacturing') }}</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['addons__manage_task'] == 1)
                    @if ($generalSettings['modules__manage_task'] == '1')
                        @if (auth()->user()->can('assign_todo') ||
                                auth()->user()->can('work_space') ||
                                auth()->user()->can('memo') ||
                                auth()->user()->can('msg'))
                            <li data-menu="essentials" class="{{ request()->is('essentials*') ? 'menu_active' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('backend/asset/img/icon/to-do-list.svg') }}">
                                    <p class="title">{{ __('Manage Task') }}</p>
                                </a>
                            </li>
                        @endif
                    @endif
                @endif

                {{-- @if ($generalSettings['addons__service'] == 1)
                    <li class="">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/service.svg') }}">
                            <p class="title">@lang('menu.service')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['addons__e_commerce'] == 1)
                    <li class="">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/ecommerce2.svg') }}">
                            <p class="title">@lang('menu.e_commerce')</p>
                        </a>
                    </li>
                @endif --}}

                <li data-menu="communication" class="{{ request()->is('communication*') ? 'menu_active' : '' }}">
                    <a href="#">
                        <img src="{{ asset('backend/asset/img/icon/communication.svg') }}">
                        <p class="title">{{ __('Communicate') }}</p>
                    </a>
                </li>

                @if (auth()->user()->can('branch') ||
                        auth()->user()->can('warehouse') ||
                        auth()->user()->can('tax') ||
                        auth()->user()->can('general_settings') ||
                        auth()->user()->can('payment_settings') ||
                        auth()->user()->can('invoice_schema') ||
                        auth()->user()->can('invoice_layout') ||
                        auth()->user()->can('barcode_settings') ||
                        auth()->user()->can('cash_counters'))
                    <li data-menu="settings" class="{{ request()->is('settings*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/settings.svg') }}">
                            <p class="title">{{ __('Set-up') }}</p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('tax_report'))
                    <li data-menu="reports" class="{{ request()->is('reports*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/business-report.svg') }}">
                            <p class="title">@lang('menu.reports')</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="category-bar">
        <div id="sidebar_t">
            <div class="sub-menu_t" id="product">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Product Management') }}</p>
                            <div class="sub-menu-row">
                                @if (auth()->user()->can('product_add'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('products.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-plus-circle"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Add Product') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_all'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('products.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-sitemap"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Product List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_import'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('product.import.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-file-import"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.import_products')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_expired_list'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('expired.products.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-sitemap"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Expired Product List') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('product_category_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('categories.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-th-large"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Categories') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_brand_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('brands.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-band-aid"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Brands') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_unit_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('units.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-weight-hanging"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Units') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_variant_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('product.bulk.variants.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-align-center"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Bulk Variants') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_warranty_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('warranties.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-shield-alt"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Warranties') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('selling_price_group_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('selling.price.groups.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-layer-group"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Selling Price Groups') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('generate_barcode'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('barcode.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-barcode"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Generate Barcode') }}</p>
                                        </a>
                                    </div>
                                @endif

                                {{-- @can('product_settings')
                                    <div class="sub-menu-col">
                                        <a href="{{ route('product.settings.index') }}" class="switch-bar-wrap settings-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-sliders-h"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Product Settings') }}</p>
                                        </a>
                                    </div>
                                @endcan --}}
                            </div>
                        </div>

                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Product Reports') }}</p>
                            @if (auth()->user()->can('stock_report') ||
                                    auth()->user()->can('stock_in_out_report'))
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('stock_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.stock.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sitemap"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Stock Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('stock_in_out_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.stock.in.out.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cubes"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Stock In-Out Report') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="sub-menu_t" id="superadmin">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Superadmin') }}</p>
                            <div class="sub-menu-row">
                                @if (auth()->user()->can('branch'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('branches.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-project-diagram"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Shops') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            @if ($generalSettings['modules__contacts'] == '1')
                <div class="sub-menu_t" id="contact">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2 mb-3">
                            <div class="row align-items-center justify-content-end">
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{{ __('Contact Management') }}</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('supplier_all'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('contacts.manage.supplier.index', \App\Enums\ContactType::Supplier->value) }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-address-card"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Suppliers') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('supplier_import'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('contacts.suppliers.import.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-file-import"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Import Suppliers') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('customer_all'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('contacts.manage.customer.index', \App\Enums\ContactType::Customer->value) }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-address-card"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Customers') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('customer_import'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('contacts.customers.import.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-file-upload"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Import Customer') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('customer_group'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('contacts.customers.groups.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-users"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Customer Groups') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (auth()->user()->can('supplier_report') ||
                                    auth()->user()->can('customer_report'))
                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Contact Reports') }}</p>
                                    <div class="sub-menu-row">
                                        @if (auth()->user()->can('supplier_report') &&
                                                auth()->user()->can('supplier_report'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.supplier.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-id-card"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Supplier Reports') }}</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('customer_report'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.customer.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="far fa-id-card"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Customer Reports') }}</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($generalSettings['modules__purchases'] == '1')
                @if (auth()->user()->can('purchase_all') ||
                        auth()->user()->can('purchased_product_list') ||
                        auth()->user()->can('purchase_add') ||
                        auth()->user()->can('purchase_settings') ||
                        auth()->user()->can('purchase_order_index') ||
                        auth()->user()->can('purchase_order_add') ||
                        auth()->user()->can('purchase_return_index') ||
                        auth()->user()->can('purchase_return_add') ||
                        auth()->user()->can('purchase_report') ||
                        auth()->user()->can('purchase_order_report') ||
                        auth()->user()->can('purchase_ordered_product_report') ||
                        auth()->user()->can('purchase_return_report') ||
                        auth()->user()->can('purchase_returned_product_report') ||
                        auth()->user()->can('product_purchase_report') ||
                        auth()->user()->can('purchase_sale_report') ||
                        auth()->user()->can('product_purchase_report') ||
                        auth()->user()->can('purchase_payment_report'))
                    <div class="sub-menu_t" id="purchases">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2 mb-3">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                @if (auth()->user()->can('purchase_all') ||
                                        auth()->user()->can('purchased_product_list') ||
                                        auth()->user()->can('purchase_add') ||
                                        auth()->user()->can('purchase_settings'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Purchase Management') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('purchase_add'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchases.create') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-plus-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Add Purchase') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_all'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchases.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase List') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_all'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchases.products.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchased Product List') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            {{-- @if (auth()->user()->can('purchase_settings'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchase.settings.index') }}" class="switch-bar-wrap settings-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-sliders-h"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __("Purchase Settings") }}</p>
                                                    </a>
                                                </div>
                                            @endif --}}
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('purchase_order_index') ||
                                        auth()->user()->can('purchase_order_add'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Purchase Order Management') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('purchase_order_add'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchase.orders.create') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-plus-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text"> {{ __('Add Purchase Order') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_order_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchase.orders.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('P/o List') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('purchase_return_index') ||
                                        auth()->user()->can('purchase_return_add'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Purchase Return Management') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('purchase_return_add'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchase.returns.create') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-plus-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text"> {{ __('Add Purchase Return') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_return_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('purchase.returns.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Return List') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('purchase_report') ||
                                        auth()->user()->can('purchase_order_report') ||
                                        auth()->user()->can('purchase_ordered_product_report') ||
                                        auth()->user()->can('purchase_return_report') ||
                                        auth()->user()->can('purchase_returned_product_report') ||
                                        auth()->user()->can('product_purchase_report') ||
                                        auth()->user()->can('purchase_sale_report') ||
                                        auth()->user()->can('product_purchase_report') ||
                                        auth()->user()->can('purchase_payment_report'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('PURCHASE REPORTS') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('purchase_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchases.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_ordered_product_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchased.products.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchased Products Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_order_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchase.orders.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Order Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_ordered_product_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchase.ordered.products.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Ordered Products Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_return_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchase.returns.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Return Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_returned_product_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchase.returned.products.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Purchase Returned Products Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_payment_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.payment.against.purchase.report') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Payments Against Purchase Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('purchase_sale_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.sales.purchases.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Sale Vs Purchase') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="sub-menu_t" id="sales">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Sale Management') }}</p>
                            <div class="sub-menu-row">

                                @if ($generalSettings['modules__add_sale'] == '1')

                                    @if (auth()->user()->can('create_add_sale'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-circle"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text"> {{ __('Add Sale') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('view_add_sale'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Manage Add Sales') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    {{-- @if (auth()->user()->can('add_sale_settings'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('add.sales.settings.edit') }}" class="switch-bar-wrap settings-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Add Sales Settings') }}</p>
                                            </a>
                                        </div>
                                    @endif --}}
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if ($generalSettings['modules__pos'] == '1')

                                    @if (auth()->user()->can('pos_add'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.pos.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-circle"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('POS') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('pos_all'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.pos.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('POS Sale List') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    {{-- @if (auth()->user()->can('pos_sale_settings'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('pos.sales.settings.edit') }}" class="switch-bar-wrap settings-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.pos_sale_settings')</p>
                                            </a>
                                        </div>
                                    @endif --}}
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('sales_order_list'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.orders.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Sales Order List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('sale_quotation'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.quotations.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Quotation List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('sale_draft'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.drafts.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Draft List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('sold_product_list'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.products.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Sold Product List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('shipment_access'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.shipments.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-shipping-fast"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Shipment List') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('sales_return_index'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.returns.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-plus-circle"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Add Sales Return') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('create_sales_return'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.returns.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Sales Return List') }}</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('discounts'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.discounts.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-percentage"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Discounts') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('create_add_sale'))
                                    <div class="sub-menu-col">
                                        <a href="#" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-plus-circle"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Sales Order To Invoice') }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if (auth()->user()->can('sales_report') ||
                                auth()->user()->can('sales_return_report') ||
                                auth()->user()->can('sold_product_report') ||
                                auth()->user()->can('sales_order_report') ||
                                auth()->user()->can('sales_ordered_products_report') ||
                                auth()->user()->can('sales_returned_products_report') ||
                                auth()->user()->can('received_against_sales_report') ||
                                auth()->user()->can('cash_register_report'))

                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{{ __('Sale Reports') }}</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('sales_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sales.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sales Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('product_sale_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sold.products.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sold Products Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('sales_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sales.order.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sales Order Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('sales_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sales.ordered.products.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sales Ordered Products Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('sales_return_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sales.return.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sales Return Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('sales_returned_products_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sales.returned.products.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Sales Returned Products Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('received_against_sales_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.receive.against.sales.report') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Received Against Sales Report') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('cash_register_report'))
                                        <div class="sub-menu-col">
                                            <a href="#" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Cash Register Report') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($generalSettings['modules__transfer_stock'] == '1')
                @if (auth()->user()->can('transfer_stock_index') ||
                        auth()->user()->can('transfer_stock_create') ||
                        auth()->user()->can('transfer_stock_receive_from_warehouse') ||
                        auth()->user()->can('transfer_stock_receive_from_branch'))
                    <div class="sub-menu_t" id="transfer">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2 mb-3">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                @if (auth()->user()->can('transfer_stock_index') ||
                                        auth()->user()->can('transfer_stock_create'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Transfer Stock') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('transfer_stock_create'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('transfer.stocks.create') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-exchange-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Add Transfer Stock') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('transfer_stock_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('transfer.stocks.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list-ul"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Transfer Stock List') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('transfer_stock_receive_from_warehouse') ||
                                        auth()->user()->can('transfer_stock_receive_from_branch'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Receive Transferred Stocks') }} <strong></strong></p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('transfer_stock_receive_from_warehouse'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('receive.stock.from.warehouse.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-exchange-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Receive From Warehouse') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('transfer_stock_receive_from_branch'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('receive.stock.from.branch.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list-ul"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Receive From Shop/Business') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if ($generalSettings['modules__stock_adjustments'] == '1')

                @if (auth()->user()->can('stock_adjustment_add') ||
                        auth()->user()->can('stock_adjustment_list') ||
                        auth()->user()->can('stock_adjustment_report') ||
                        auth()->user()->can('stock_adjustment_product_report'))
                    <div class="sub-menu_t" id="adjustment">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2 mb-3">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                @if (auth()->user()->can('stock_adjustment_add') ||
                                        auth()->user()->can('stock_adjustment_list'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Stock Adjustment') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('stock_adjustment_add'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('stock.adjustments.create') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-plus-circle"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Add Stock Adjustment') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('stock_adjustment_list'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('stock.adjustments.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Stock Adjustmen List') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('stock_adjustment_report') ||
                                        auth()->user()->can('stock_adjustment_product_report'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Stock Adjustment Reports') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('stock_adjustment_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.stock.adjustments.report.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Stock Adjustment Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('stock_adjustment_product_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.stock.adjusted.products.report.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-list"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Stock Adjusted Products Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if ($generalSettings['modules__accounting'] == '1')

                @if (auth()->user()->can('banks_index') ||
                        auth()->user()->can('account_groups_index') ||
                        auth()->user()->can('accounts_index') ||
                        auth()->user()->can('capital_accounts_index') ||
                        auth()->user()->can('duties_and_taxes_index') ||
                        auth()->user()->can('receipts_index') ||
                        auth()->user()->can('payments_index') ||
                        auth()->user()->can('expenses_index') ||
                        auth()->user()->can('contras_index') ||
                        auth()->user()->can('profit_loss') ||
                        auth()->user()->can('financial_report') ||
                        auth()->user()->can('profit_loss_account') ||
                        auth()->user()->can('balance_sheet') ||
                        auth()->user()->can('trial_balance') ||
                        auth()->user()->can('cash_flow'))
                    <div class="sub-menu_t" id="accounting">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2 mb-3">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                @if (auth()->user()->can('banks_index') ||
                                        auth()->user()->can('account_groups_index') ||
                                        auth()->user()->can('accounts_index') ||
                                        auth()->user()->can('capital_accounts_index') ||
                                        auth()->user()->can('duties_and_taxes_index'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Account Management') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('banks_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('banks.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-university"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Banks') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('account_groups_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('account.groups.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Account Groups') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('accounts_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounts.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Accounts') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('capital_accounts_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounts.capitals.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Capital Accoounts') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('duties_and_taxes_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounts.duties.taxes.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Duties And Taxes') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('receipts_index') ||
                                        auth()->user()->can('payments_index') ||
                                        auth()->user()->can('expenses_index') ||
                                        auth()->user()->can('contras_index'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Accounting Vouchers') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('receipts_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('receipts.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Receipts') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('payments_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('payments.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Payments') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('expenses_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('expenses.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Expenses') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('contras_index'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('contras.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Contras') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if (auth()->user()->can('profit_loss') ||
                                        auth()->user()->can('financial_report') ||
                                        auth()->user()->can('profit_loss_account') ||
                                        auth()->user()->can('balance_sheet') ||
                                        auth()->user()->can('trial_balance') ||
                                        auth()->user()->can('cash_flow'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Account Reports') }}</p>
                                        <div class="sub-menu-row">
                                            @if (auth()->user()->can('profit_loss'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.profit.loss.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-chart-line"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Profit/Loss') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('financial_report'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.financial.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-bill-wave"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Finalcial Report') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('profit_loss_account'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounting.profit.loss.account') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-chart-line"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">@lang('menu.profit_loss_account')</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('balance_sheet'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounting.balance.sheet') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-balance-scale"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Balance Sheet') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('trial_balance'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounting.trial.balance') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-balance-scale-right"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Trial Balance') }}</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (auth()->user()->can('cash_flow'))
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('accounting.cash.flow') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-bill-wave"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">{{ __('Cash Flow') }}</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (auth()->user()->can('user_add') ||
                    auth()->user()->can('user_view') ||
                    auth()->user()->can('role_add') ||
                    auth()->user()->can('role_view'))
                <div class="sub-menu_t" id="users">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2 mb-3">
                            <div class="row align-items-center justify-content-end">
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{{ __('User Management') }}</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('user_add'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('users.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-circle"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Add User') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('user_view'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('users.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('User List') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('role_add'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('users.role.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-circle"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Add Role') }}</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('role_view'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('users.role.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __('Role List') }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($generalSettings['addons__hrm'] == 1)
                <div class="sub-menu_t" id="hrm">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2 mb-3">
                            <div class="row align-items-center justify-content-end">
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="container-fluid">
                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{{ __('Human Resource Management System') }}</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('hrm_dashboard'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.dashboard.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-tachometer-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.hrm_dashboard')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('leave_type'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.leave.type.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-th-large"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.leave_type')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('leave_assign'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.leaves.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-level-down-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.leave')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('shift'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.attendance.shift') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-network-wired"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.shift')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('attendance'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.attendance') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-paste"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.attendance')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('view_allowance_and_deduction'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.allowance') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.allowance_deduction')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('payroll'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.payroll.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-money-bill-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.payroll')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('holiday'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.holidays') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-toggle-off"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.holiday')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('department'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.departments') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-building"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.department')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('designation'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('hrm.designations') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-map-marker-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.designation')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (
                                (auth()->user()->can('payroll_report') &&
                                    auth()->user()->can('payroll_report')) ||
                                    (auth()->user()->can('payroll_payment_report') &&
                                        auth()->user()->can('payroll_payment_report')) ||
                                    (auth()->user()->can('attendance_report') &&
                                        auth()->user()->can('attendance_report')))
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>HRM Reports</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">HRM @lang('menu.report')</p>
                                    <div class="sub-menu-row">

                                        @if (auth()->user()->can('payroll_report') &&
                                                auth()->user()->can('payroll_report'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.payroll') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-money-bill-alt"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.payroll_report')</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('payroll_payment_report') &&
                                                auth()->user()->can('payroll_payment_report'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.payroll.payment') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-money-bill-alt"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.payroll_payment_report')</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('attendance_report') &&
                                                auth()->user()->can('attendance_report'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.attendance') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-paste"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.attendance_report')</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="sub-menu_t" id="settings">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Set-up') }}</p>
                            <div class="sub-menu-row">
                                @if (auth()->user()->can('general_settings'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('settings.general.index') }}" class="switch-bar-wrap settings-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-cogs"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.general_settings')</p>
                                        </a>
                                    </div>
                                @endif


                                @if (auth()->user()->can('branch'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('branches.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-project-diagram"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">{{ __('Shops') }}</p>
                                        </a>
                                    </div>
                                @endif


                                @if (auth()->user()->can('warehouse'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('warehouses.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-warehouse"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.warehouses') </p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('payment_settings'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('payment.methods.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-credit-card"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.payment_methods')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('invoice_schema'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('invoices.schemas.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-file-invoice-dollar"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.invoice_schema')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('invoice_layout'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('invoices.layouts.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-file-invoice"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.invoice_layout')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('barcode_settings'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('barcode.settings.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-barcode"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.barcode_settings')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('cash_counters'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('cash.counters.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-store"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.cash_counter')</p>
                                        </a>
                                    </div>
                                @endif

                                <div class="sub-menu-col">
                                    <a href="{{ route('software.service.billing.index') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="far fa-arrow-alt-circle-up"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">{{ __('Billing') }}</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('settings.release.note.index') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="far fa-arrow-alt-circle-up"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.version_release_notes')</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('barcode.settings.design.pages') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="far fa-arrow-alt-circle-up"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">{{ __('Barcode Settings Design Pages') }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($generalSettings['addons__manufacturing'] == 1)
                @if (auth()->user()->can('process_view') ||
                        auth()->user()->can('production_view') ||
                        auth()->user()->can('manufacturing_settings') ||
                        auth()->user()->can('manufacturing_report'))
                    <div class="sub-menu_t" id="manufacture">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2 mb-3">
                                <div class="row align-items-center justify-content-end">
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Manufacturing') }}</p>
                                    <div class="sub-menu-row">
                                        @if (auth()->user()->can('process_view'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('manufacturing.process.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-dumpster-fire"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Process/Bill Of Material') }}</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('production_view'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('manufacturing.productions.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-shapes"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Productions') }}</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('manufacturing_settings'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('manufacturing.settings.index') }}" class="switch-bar-wrap settings-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-sliders-h"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Manufacturing Setting') }}</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if (auth()->user()->can('manufacturing_report'))
                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __('Manufacturing Reports') }}</p>
                                        <div class="sub-menu-row">
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.production.report.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-list"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Production Report') }}</p>
                                                </a>
                                            </div>

                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.ingredients.report.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-list"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __('Ingredients Report') }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="sub-menu_t" id="essentials">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Task Management') }}</p>
                            <div class="sub-menu-row">
                                @if (auth()->user()->can('assign_todo'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('todo.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-th-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.todo')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('work_space'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('workspace.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-th-large"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.work_space')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('memo'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('memos.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-file-alt"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.memo')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('msg'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('messages.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-envelope"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.message')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sub-menu_t" id="communication">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">@lang('menu.communication')</p>
                            <div class="sub-menu-row">
                                <div class="sub-menu-col">
                                    <a href="" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-exclamation"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.notice_board')</p>
                                    </a>
                                </div>
                            </div>

                            <div class="sub-menu-row">
                                <div class="sub-menu-col">
                                    <a href="" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="far fa-envelope"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.email')</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('communication.email.settings') }}" class="switch-bar-wrap settings-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.email_settings')</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('communication.email.settings.server.setup.design.pages') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">{{ __('Email Server Setup Design Pages') }}</p>
                                    </a>
                                </div>
                            </div>

                            <div class="sub-menu-row">
                                <div class="sub-menu-col">
                                    <a href="" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-sms"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.sms')</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('communication.sms.settings') }}" class="switch-bar-wrap settings-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">{{ __('SMS Settings') }}</p>
                                    </a>
                                </div>

                                <div class="sub-menu-col">
                                    <a href="{{ route('communication.sms.settings.server.setup.design.pages') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">{{ __('SMS Server Setup Design Pages') }}</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sub-menu_t" id="reports">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2 mb-3">
                        <div class="row align-items-center justify-content-end">
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-secondary close-model float-end"><i class="fas fa-times"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Common Reports') }}</p>
                            <div class="sub-menu-row">
                                @if (auth()->user()->can('tax_report'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('reports.taxes.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-percent"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.tax_report')</p>
                                        </a>
                                    </div>
                                @endif

                                <div class="sub-menu-col">
                                    <a href="{{ route('reports.user.activities.log.index') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fas fa-clipboard-list"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.user_activities_log')</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
