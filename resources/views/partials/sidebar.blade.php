<div id="primary_nav" class="g_blue toggle-leftbar">
    <div class="first__left">
        <div class="main__nav">
            <ul id="" class="float-right">
                <li data-menu="dashboardmenu" class="">
                    <a href="{{ route('dashboard.dashboard') }}" class="">
                        <img src="{{ asset('backend/asset/img/icon/pie-chart.svg') }}" alt="">
                        <p class="title">@lang('menu.dashboard')</p>
                    </a>
                </li>

                @if ($generalSettings['addons__branches'] == 1)
                    <li data-menu="superadmin" class="">
                        <a href="#" class=""><img src="{{ asset('backend/asset/img/icon/superadmin.svg') }}">
                            <p class="title">@lang('menu.superadmin')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__contacts'] == '1')
                    @if (
                        auth()->user()->can('supplier_all') ||
                        auth()->user()->can('supplier_add') ||
                        auth()->user()->can('supplier_import') ||
                        auth()->user()->can('customer_all') ||
                        auth()->user()->can('customer_add') ||
                        auth()->user()->can('customer_import') ||
                        auth()->user()->can('customer_group') ||
                        (
                            auth()->user()->can('supplier_report') &&
                            auth()->user()->can('supplier_report')
                        ) ||
                        (
                            auth()->user()->can('customer_report') &&
                            auth()->user()->can('customer_report')
                        )
                    )
                        <li data-menu="contact" class="{{ request()->is('contacts*') ? 'menu_active' : '' }}">
                            <a href="#" class=""><img src="{{ asset('backend/asset/img/icon/agenda.svg') }}">
                                <p class="title">@lang('menu.contacts')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (
                    auth()->user()->can('product_all') ||
                    auth()->user()->can('product_add') ||
                    auth()->user()->can('categories') ||
                    auth()->user()->can('brand') ||
                    auth()->user()->can('units') ||
                    auth()->user()->can('variant') ||
                    auth()->user()->can('warranties') ||
                    auth()->user()->can('selling_price_group') ||
                    auth()->user()->can('generate_barcode') ||
                    (
                        auth()->user()->can('product_settings') &&
                        auth()->user()->can('product_settings')
                    ) ||
                    (
                        auth()->user()->can('stock_report') &&
                        auth()->user()->can('stock_report')
                    ) ||
                    (
                        auth()->user()->can('stock_in_out_report') &&
                        auth()->user()->can('stock_in_out_report')
                    )
                )
                    <li data-menu="product" class="{{ request()->is('product*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/package.svg') }}" alt="">
                            <p class="title">@lang('menu.product')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__purchases'] == '1')

                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                        <li data-menu="purchases" class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                            <a href="#" class="">
                                <img src="{{ asset('backend/asset/img/icon/bill.svg') }}">
                                <p class="title">@lang('menu.purchases')</p>
                            </a>
                        </li>
                    @else
                        @if (auth()->user()->branch_id && auth()->user()->branch->purchase_permission == 1)

                            @if (auth()->user()->can('purchase_all'))

                                <li data-menu="purchases" class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                                    <a href="#" class="">
                                        <img src="{{ asset('backend/asset/img/icon/bill.svg') }}">
                                        <p class="title">@lang('menu.purchases')</p>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif
                @endif

                @if (
                    auth()->user()->can('pos_all') ||
                    auth()->user()->can('pos_add') ||
                    auth()->user()->can('create_add_sale') ||
                    auth()->user()->can('view_add_sale') ||
                    auth()->user()->can('sale_draft') ||
                    auth()->user()->can('sale_quotation') ||
                    auth()->user()->can('shipment_access') ||
                    auth()->user()->can('return_access') ||
                    (
                        auth()->user()->can('pos_sale_settings') &&
                        auth()->user()->can('pos_sale_settings')
                    ) ||
                    (
                        auth()->user()->can('add_sale_settings') &&
                        auth()->user()->can('add_sale_settings')
                    ) ||
                    (
                        auth()->user()->can('discounts') &&
                        auth()->user()->can('discounts')
                    ) ||
                    (
                        auth()->user()->can('sale_statements') &&
                        auth()->user()->can('sale_statements')
                    ) ||
                    (
                        auth()->user()->can('sale_return_statements') &&
                        auth()->user()->can('sale_return_statements')
                    ) ||
                    (
                        auth()->user()->can('pro_sale_report') &&
                        auth()->user()->can('pro_sale_report')
                    ) ||
                    (
                        auth()->user()->can('sale_payment_report') &&
                        auth()->user()->can('sale_payment_report')
                    ) ||
                    (
                        auth()->user()->can('c_register_report') &&
                        auth()->user()->can('c_register_report')
                    ) ||
                    (
                        auth()->user()->can('sale_representative_report') &&
                        auth()->user()->can('sale_representative_report')
                    )
                )
                    <li data-menu="sales" class="{{ request()->is('sales*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/shopping-bag.svg') }}">
                            <p class="title">@lang('menu.sales')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__transfer_stock'] == '1')

                    <li data-menu="transfer" class="{{ request()->is('transfer/stocks*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/transfer.svg') }}">
                            <p class="title">@lang('menu.transfer')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['modules__stock_adjustment'] == '1')

                    @if (
                        auth()->user()->can('adjustment_all') ||
                        auth()->user()->can('adjustment_add_from_location') ||
                        auth()->user()->can('adjustment_add_from_warehouse') ||
                        (
                            auth()->user()->can('stock_adjustment_report') &&
                            auth()->user()->can('stock_adjustment_report')
                        )
                    )
                        <li data-menu="adjustment"
                            class="{{ request()->is('stock/adjustments*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/slider-tool.svg') }}">
                                <p class="title">@lang('menu.adjustment')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['modules__expenses'] == '1')

                    @if (
                        auth()->user()->can('view_expense') ||
                        auth()->user()->can('add_expense') ||
                        auth()->user()->can('expense_category') ||
                        auth()->user()->can('category_wise_expense') ||
                        (
                            auth()->user()->can('expanse_report') &&
                            auth()->user()->can('expanse_report')
                        )
                    )
                        <li data-menu="expenses" class="{{ request()->is('expenses*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/budget.svg') }}">
                                <p class="title">@lang('menu.expenses')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['modules__accounting'] == '1')

                    @if (auth()->user()->can('ac_access'))
                        <li data-menu="accounting" class="{{ request()->is('accounting*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/accounting.svg') }}">
                                <p class="title">@lang('menu.accounting')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (
                    auth()->user()->can('user_view') ||
                    auth()->user()->can('user_add') ||
                    auth()->user()->can('role_view') ||
                    auth()->user()->can('role_add')
                )
                    <li data-menu="users" class="{{ request()->is('users*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/team.svg') }}">
                            <p class="title">@lang('menu.users')</p>
                        </a>
                    </li>
                @endif

                @if ($generalSettings['addons__hrm'])
                    @if (
                        auth()->user()->can('hrm_dashboard') ||
                        auth()->user()->can('leave_type') ||
                        auth()->user()->can('leave_assign') ||
                        auth()->user()->can('shift') ||
                        auth()->user()->can('attendance') ||
                        auth()->user()->can('view_allowance_and_deduction') ||
                        auth()->user()->can('payroll') ||
                        auth()->user()->can('department') ||
                        auth()->user()->can('designation') ||
                        (
                            auth()->user()->can('payroll_report') &&
                            auth()->user()->can('payroll_report')
                        )
                            ||
                        (
                            auth()->user()->can('payroll_payment_report') &&
                            auth()->user()->can('payroll_payment_report')
                        )
                            ||
                        (
                            auth()->user()->can('attendance_report') &&
                            auth()->user()->can('attendance_report')
                        )
                    )
                        <li data-menu="hrm" class="{{ request()->is('hrm*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/human-resources.svg') }}">
                                <p class="title">@lang('menu.hrm')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['addons__manufacturing'] == 1)

                    @if (
                        auth()->user()->can('process_view') ||
                        auth()->user()->can('production_view') ||
                        auth()->user()->can('manuf_settings') ||
                        auth()->user()->can('manuf_report')
                    )
                        <li data-menu="manufacture" class="{{ request()->is('manufacturing*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('backend/asset/img/icon/conveyor.svg') }}">
                                <p class="title">@lang('menu.manufacturing')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($generalSettings['addons__todo'] == 1)

                    @if ($generalSettings['modules__requisite'] == '1')

                        @if (
                            auth()->user()->can('assign_todo') ||
                            auth()->user()->can('work_space') ||
                            auth()->user()->can('memo') ||
                            auth()->user()->can('msg')
                        )
                            <li data-menu="essentials" class="{{ request()->is('essentials*') ? 'menu_active' : '' }}">
                                <a href="#">
                                    <img src="{{ asset('backend/asset/img/icon/to-do-list.svg') }}">
                                    <p class="title">@lang('menu.manage_task')</p>
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
                        <p class="title">@lang('menu.communicate')</p>
                    </a>
                </li>

                @if (
                    auth()->user()->can('branch') ||
                    auth()->user()->can('warehouse') ||
                    auth()->user()->can('tax') ||
                    auth()->user()->can('g_settings') ||
                    auth()->user()->can('p_settings') ||
                    auth()->user()->can('inv_sc') ||
                    auth()->user()->can('inv_lay') ||
                    auth()->user()->can('barcode_settings') ||
                    auth()->user()->can('cash_counters')
                )
                    <li data-menu="settings" class="{{ request()->is('settings*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('backend/asset/img/icon/settings.svg') }}">
                            <p class="title">@lang('menu.setup')</p>
                        </a>
                    </li>
                @endif

                @if (
                    auth()->user()->can('tax_report')
                )
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
                            <p class="sub-menu-group-title">{{ __("Product Management") }}</p>
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
                                            <p class="switch_text">@lang('menu.add_product')</p>
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
                                            <p class="switch_text">@lang('menu.product_list')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('product_add'))
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

                                <div class="sub-menu-col">
                                    <a href="{{ route('products.expired.products') }}" class="switch-bar-wrap">
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
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('categories'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('categories.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-th-large"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.categories')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('brand'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('brands.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-band-aid"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.brand')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('units'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('units.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-weight-hanging"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.units')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('variant'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('product.variants.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-align-center"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.variants')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('warranties'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('warranties.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-shield-alt"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.warranties')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('selling_price_group'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('selling.price.groups.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span>
                                                        <i class="fas fa-layer-group"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.selling_price_group')</p>
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
                                            <p class="switch_text">@lang('menu.generate_barcode')</p>
                                        </a>
                                    </div>
                                @endif

                                @can('product_settings')
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
                                @endcan
                            </div>
                        </div>

                        <div class="sub-menu-group">
                            <p class="sub-menu-group-title">{{ __('Product Reports') }}</p>
                            @if (
                                (
                                    auth()->user()->can('stock_report') &&
                                    auth()->user()->can('stock_report')
                                )
                                    ||
                                (
                                    auth()->user()->can('stock_in_out_report') &&
                                    auth()->user()->can('stock_in_out_report')
                                )
                            )

                                <div class="sub-menu-row">
                                    @if (
                                        auth()->user()->can('stock_report') &&
                                        auth()->user()->can('stock_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.stock.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sitemap"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.stock_report')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('stock_in_out_report') &&
                                        auth()->user()->can('stock_in_out_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.stock.in.out.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cubes"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.stock_in_out_report')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($generalSettings['addons__branches'] == 1)

                <div class="sub-menu_t" id="superadmin">
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
                                <p class="sub-menu-group-title">@lang('menu.superadmin')</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('branch'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('branches.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-project-diagram"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __("Shops") }}</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                                                <p class="switch_text">@lang('menu.suppliers')</p>
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
                                                <p class="switch_text">@lang('menu.import_suppliers')</p>
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
                                                <p class="switch_text">@lang('menu.customers') </p>
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
                                                <p class="switch_text">@lang('menu.import_customers')</p>
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
                                                <p class="switch_text">@lang('menu.customer_groups')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (
                                (
                                    auth()->user()->can('supplier_report') &&
                                    auth()->user()->can('supplier_report')
                                )
                                    ||
                                (
                                    auth()->user()->can('customer_report') &&
                                    auth()->user()->can('customer_report')
                                )
                            )
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>Contact Reports</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Contact Reports') }}</p>
                                    <div class="sub-menu-row">
                                        @if (
                                            auth()->user()->can('supplier_report') &&
                                            auth()->user()->can('supplier_report')
                                        )
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.supplier.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-id-card"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.supplier_report')</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (
                                            auth()->user()->can('customer_report') &&
                                            auth()->user()->can('customer_report')
                                        )
                                            <div class="sub-menu-col">
                                                <a href="{{ route('reports.customer.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="far fa-id-card"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.customer_report')</p>
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
                @canany([
                    "pro_purchase_report",
                    "purchase_add",
                    "purchase_all",
                    "purchase_delete",
                    "purchase_edit",
                    "purchase_payment",
                    "purchase_payment_report",
                    "purchase_return",
                    "purchase_sale_report",
                    "purchase_settings",
                    "purchase_statements",
                ])
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
                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Purchase Management') }}</p>
                                    <div class="sub-menu-row">
                                        @if (auth()->user()->can('purchase_add'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchases.create') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-shopping-cart"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.add_purchase')</p>
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
                                                    <p class="switch_text">@lang('menu.purchase_list')</p>
                                                </a>
                                            </div>

                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchases.products.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-list"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __("Purchased Product List") }}</p>
                                                </a>
                                            </div>
                                        @endif

                                        @if (auth()->user()->can('purchase_settings'))
                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchase.settings.index') }}" class="switch-bar-wrap settings-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-sliders-h"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.purchase_settings')</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Purchase Order Management') }}</p>
                                    @if (auth()->user()->can('purchase_return'))
                                        <div class="sub-menu-row">
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

                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchase.orders.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-list"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __("P/o List") }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Purchase Return Management') }}</p>
                                    @if (auth()->user()->can('purchase_return'))
                                        <div class="sub-menu-row">
                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchase.returns.create') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-plus-circle"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text"> {{ __("Add Purchase Return") }}</p>
                                                </a>
                                            </div>

                                            <div class="sub-menu-col">
                                                <a href="{{ route('purchase.returns.index') }}" class="switch-bar-wrap">
                                                    <div class="switch_bar">
                                                        <div class="bar-link">
                                                            <span><i class="fas fa-undo"></i></span>
                                                        </div>
                                                    </div>
                                                    <p class="switch_text">{{ __("Purchase Return List") }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (
                                    (
                                        auth()->user()->can('purchase_statements') &&
                                        auth()->user()->can('purchase_statements')
                                    ) ||
                                    (
                                        auth()->user()->can('purchase_sale_report') &&
                                        auth()->user()->can('purchase_sale_report')
                                    ) ||
                                    (
                                        auth()->user()->can('pro_purchase_report') &&
                                        auth()->user()->can('pro_purchase_report')
                                    ) ||
                                    (
                                        auth()->user()->can('purchase_payment_report') &&
                                        auth()->user()->can('purchase_payment_report')
                                    )
                                )

                                    {{-- <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                            <p class="text-muted ms-3"><strong>Purchase Reports</strong></p>
                                            <hr class="p-0 m-0 mb-3">
                                        </div>
                                    </div> --}}

                                    <div class="sub-menu-group">
                                        <p class="sub-menu-group-title">{{ __("PURCHASE REPORTS") }}</p>
                                        <div class="sub-menu-row">
                                            @if (
                                                auth()->user()->can('purchase_statements') &&
                                                auth()->user()->can('purchase_statements')
                                            )
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchases.statement.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-tasks"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">@lang('menu.purchase_statements')</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (
                                                auth()->user()->can('purchase_sale_report') &&
                                                auth()->user()->can('purchase_sale_report')
                                            )
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.sales.purchases.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="far fa-chart-bar"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">@lang('menu.purchase_sale')</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (
                                                auth()->user()->can('pro_purchase_report') &&
                                                auth()->user()->can('pro_purchase_report')
                                            )
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.product.purchases.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-shopping-cart"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">@lang('menu.product_purchase_report')</p>
                                                    </a>
                                                </div>
                                            @endif

                                            @if (
                                                auth()->user()->can('purchase_payment_report') &&
                                                auth()->user()->can('purchase_payment_report')
                                            )
                                                <div class="sub-menu-col">
                                                    <a href="{{ route('reports.purchase.payments.index') }}" class="switch-bar-wrap">
                                                        <div class="switch_bar">
                                                            <div class="bar-link">
                                                                <span><i class="fas fa-money-check-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <p class="switch_text">@lang('menu.purchase_payment_report')</p>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endcanany
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
                                                        <span><i class="fas fa-cart-plus"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text"> @lang('menu.add_sale')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('view_add_sale'))

                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.index2') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-tasks"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_sale_list')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('add_sale_settings'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('add.sales.settings.edit') }}" class="switch-bar-wrap settings-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_sale_settings')</p>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if ($generalSettings['modules__pos'] == '1')

                                    @if (auth()->user()->can('pos_add'))

                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.pos.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cash-register"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.pos')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('pos_all'))

                                        <div class="sub-menu-col">
                                            <a href="{{ route('sales.pos.list') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-tasks"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.pos_sale_list')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('pos_sale_settings'))
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
                                    @endif
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                <div class="sub-menu-col">
                                    <a href="{{ route('sales.order.list') }}" class="switch-bar-wrap">
                                        <div class="switch_bar">
                                            <div class="bar-link">
                                                <span><i class="fa fa-file-alt"></i></span>
                                            </div>
                                        </div>
                                        <p class="switch_text">@lang('menu.sales_order_list')</p>
                                    </a>
                                </div>

                                @if (auth()->user()->can('sale_draft'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.drafts') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-drafting-compass"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.draft_list')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('sale_quotation'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.quotations') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-quote-right"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.quotation_list')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (
                                    auth()->user()->can('view_add_sale') ||
                                    auth()->user()->can('pos_all')
                                )
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.product.list') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-tasks"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.sold_product_list')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('shipment_access'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.shipments') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-shipping-fast"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.shipments')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="sub-menu-row">
                                @if (auth()->user()->can('return_access'))

                                    <div class="sub-menu-col">
                                        <a href="{{ route('sale.return.random.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-undo"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_sale_return')</p>
                                        </a>
                                    </div>

                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.returns.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-undo"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.sale_return_list')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (
                                    auth()->user()->can('discounts') &&
                                    auth()->user()->can('discounts')
                                )
                                    <div class="sub-menu-col">
                                        <a href="{{ route('sales.discounts.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-percentage"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.discounts')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if (
                            (
                                auth()->user()->can('pro_sale_report') &&
                                auth()->user()->can('pro_sale_report')
                            ) ||
                            (
                                auth()->user()->can('sale_payment_report') &&
                                auth()->user()->can('sale_payment_report')
                            ) ||
                            (
                                auth()->user()->can('c_register_report') &&
                                auth()->user()->can('c_register_report')
                            ) ||
                            (
                                auth()->user()->can('sale_representative_report') &&
                                auth()->user()->can('sale_representative_report')
                            )
                        )
                            {{-- <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <p class="text-muted mt-1 ms-3"><strong>Sale Reports</strong></p>
                                    <hr class="p-0 m-0 mb-3">
                                </div>
                            </div> --}}

                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{{ __("Sale Reports") }}</p>
                                <div class="sub-menu-row">
                                    @if (
                                        auth()->user()->can('sale_statements') &&
                                        auth()->user()->can('sale_statements')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sale.statement.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-tasks"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.sale_statement')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('sale_return_statements') &&
                                        auth()->user()->can('sale_return_statements')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sale.return.statement.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-tasks"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.return_statement')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('pro_sale_report') &&
                                        auth()->user()->can('pro_sale_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.product.sales.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cart-arrow-down"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.product_sale_report')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('sale_payment_report') &&
                                        auth()->user()->can('sale_payment_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sale.payments.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-hand-holding-usd"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.sale_payment_report')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('c_register_report') &&
                                        auth()->user()->can('c_register_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.cash.registers.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cash-register"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.register_report')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (
                                        auth()->user()->can('sale_representative_report') &&
                                        auth()->user()->can('sale_representative_report')
                                    )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.sale.representative.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-user-tie"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.sales_representative_report')</p>
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
                            {{-- <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                    <p>{!! __('menu.transfer_stock_heading_1') !!}</p>
                                </div>
                            </div>
                            <hr class="p-0 m-0 mb-3"> --}}

                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{!! __('menu.transfer_stock_heading_1') !!}</p>
                                <div class="sub-menu-row">
                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stock.to.branch.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-exchange-alt"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_transfer')<small class="ml-1"><b>(@lang('menu.to_branch'))</small></b></p>
                                        </a>
                                    </div>

                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stock.to.branch.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list-ul"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.transfer_list')</p>
                                        </a>
                                    </div>

                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-check-double"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.receive_stocks')<small class="ml-1"><b>(From B.Location)</small></b></p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                    <p>{!! __('menu.transfer_stock_heading_2') !!}</p>
                                </div>
                            </div>
                            <hr class="p-0 m-0 mb-3"> --}}
                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">{!! __('menu.transfer_stock_heading_2') !!}</p>
                                <div class="sub-menu-row">
                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stock.to.warehouse.create') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-exchange-alt"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_transfer')<small class="ml-1">(@lang('menu.to_warehouse'))</small></p>
                                        </a>
                                    </div>

                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stock.to.warehouse.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list-ul"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.transfer_list')<small class="ml-1">(@lang('menu.to_warehouse'))</small></p>
                                        </a>
                                    </div>

                                    <div class="sub-menu-col">
                                        <a href="{{ route('transfer.stocks.to.branch.receive.stock.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-check-double"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.receive_stocks')</p>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if ($generalSettings['addons__branches'] == 1)
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                        <p>{!! __('menu.transfer_stock_heading_3') !!}</p>
                                    </div>
                                </div>
                                <hr class="p-0 m-0 mb-3"> --}}
                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{!! __('menu.transfer_stock_heading_3') !!}</p>
                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('transfer.stock.branch.to.branch.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-exchange-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_transfer')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('transfer.stock.branch.to.branch.transfer.list') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-list-ul"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.transfer_list')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('transfer.stock.branch.to.branch.receivable.list') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-check-double"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.receive_stocks')</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($generalSettings['modules__stock_adjustment'] == '1')
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
                            <div class="sub-menu-group">
                                <p class="sub-menu-group-title">@lang('menu.stock_adjustment')</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('adjustment_add_from_location'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('stock.adjustments.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-square"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_stock_adjustment_from_branch')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('adjustment_add_from_warehouse'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('stock.adjustments.create.from.warehouse') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-square"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_stock_adjustment_from_warehouse')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('adjustment_add_from_location'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('stock.adjustments.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-th-list"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.stock_adjustment_list')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (
                                (
                                    auth()->user()->can('stock_adjustment_report') &&
                                    auth()->user()->can('stock_adjustment_report')
                                )
                            )
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>{{ __('Stock Adjustment Reports') }}</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Stock Adjustment Reports') }}</p>
                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.stock.adjustments.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.stock_adjustment_report')</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($generalSettings['modules__expenses'] == '1')
                <div class="sub-menu_t" id="expenses">
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
                                <p class="sub-menu-group-title">{{ __('Expense Management') }}</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('add_expense') )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('expanses.create') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-plus-square"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.add_expense')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('expense_category') )
                                        <div class="sub-menu-col">
                                            <a href="{{ route('expenses.categories.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-cubes"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.expense_categories')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('view_expense'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('expanses.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-list-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.expense_list')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if (
                                (
                                    auth()->user()->can('expanse_report') &&
                                    auth()->user()->can('expanse_report')
                                )
                            )
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>@lang('menu.expense_report')</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">@lang('menu.expense_report')</p>
                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.expenses.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-money-bill-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.expense_report')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.expenses.category.wise.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="far fa-list-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.category_wise_expense_report')</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($generalSettings['modules__accounting'] == '1')

                @if (auth()->user()->can('ac_access'))

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
                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __("Account Management") }}</p>
                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('banks.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-university"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __("Banks") }}</p>
                                            </a>
                                        </div>

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

                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounts.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-money-check-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.accounts')</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.assets.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-luggage-cart"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.assets')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.loan.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-hand-holding-usd"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.loans')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.contras.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-hand-holding-usd"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.contra')</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.balance.sheet') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-balance-scale"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.balance_sheet')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.trial.balance') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-balance-scale-right"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.trial_balance')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('accounting.cash.flow') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-money-bill-wave"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.cash_flow')</p>
                                            </a>
                                        </div>

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
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>Account Reports</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">{{ __('Account Reports') }}</p>
                                    <div class="sub-menu-row">
                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.profit.loss.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-chart-line"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.daily_profit_loss_report')</p>
                                            </a>
                                        </div>

                                        <div class="sub-menu-col">
                                            <a href="{{ route('reports.financial.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-money-bill-wave"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.financial_report')</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

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
                                                    <span><i class="fas fa-user-plus"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_user')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('user_view'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('users.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-list-ol"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.user_list')</p>
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
                                            <p class="switch_text">@lang('menu.add_role')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('role_view'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('users.role.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-th-list"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.role_list')</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    @if ( auth()->user()->can('hrm_dashboard'))
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
                                (
                                    auth()->user()->can('payroll_report') &&
                                    auth()->user()->can('payroll_report')
                                )
                                    ||
                                (
                                    auth()->user()->can('payroll_payment_report') &&
                                    auth()->user()->can('payroll_payment_report')
                                )
                                    ||
                                (
                                    auth()->user()->can('attendance_report') &&
                                    auth()->user()->can('attendance_report')
                                )
                            )
                                {{-- <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <p class="text-muted mt-1 ms-3"><strong>HRM Reports</strong></p>
                                        <hr class="p-0 m-0 mb-3">
                                    </div>
                                </div> --}}

                                <div class="sub-menu-group">
                                    <p class="sub-menu-group-title">HRM @lang('menu.report')</p>
                                    <div class="sub-menu-row">

                                        @if (
                                            auth()->user()->can('payroll_report') &&
                                            auth()->user()->can('payroll_report')
                                        )
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

                                        @if (
                                            auth()->user()->can('payroll_payment_report') &&
                                            auth()->user()->can('payroll_payment_report')
                                        )
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

                                        @if (
                                            auth()->user()->can('attendance_report') &&
                                            auth()->user()->can('attendance_report')
                                        )
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
                                @if (auth()->user()->can('g_settings'))
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

                                @if ($generalSettings['addons__branches'] == 1)
                                    @if (auth()->user()->can('branch'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('branches.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-project-diagram"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">{{ __("Shops") }}</p>
                                            </a>
                                        </div>
                                    @endif
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

                                @if (auth()->user()->can('tax'))
                                    <div class="sub-menu-col">
                                        <a href="{{ route('settings.taxes.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-percentage"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.taxes')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('p_settings'))
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

                                    <div class="sub-menu-col">
                                        <a href="{{ route('payment.methods.settings.index') }}" class="switch-bar-wrap">
                                            <div class="switch_bar">
                                                <div class="bar-link">
                                                    <span><i class="fas fa-credit-card"></i></span>
                                                </div>
                                            </div>
                                            <p class="switch_text">@lang('menu.payment_method_settings')</p>
                                        </a>
                                    </div>
                                @endif

                                @if (auth()->user()->can('inv_sc'))
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

                                @if (auth()->user()->can('inv_lay'))
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
                                <p class="sub-menu-group-title">@lang('menu.manufacturing')</p>
                                <div class="sub-menu-row">
                                    @if (auth()->user()->can('process_view'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('manufacturing.process.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-dumpster-fire"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.process')</p>
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
                                                <p class="switch_text">@lang('menu.productions')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('manuf_settings'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('manufacturing.settings.index') }}" class="switch-bar-wrap settings-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-sliders-h"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.manufacturing_setting')</p>
                                            </a>
                                        </div>
                                    @endif

                                    @if (auth()->user()->can('manuf_report'))
                                        <div class="sub-menu-col">
                                            <a href="{{ route('manufacturing.report.index') }}" class="switch-bar-wrap">
                                                <div class="switch_bar">
                                                    <div class="bar-link">
                                                        <span><i class="fas fa-file-alt"></i></span>
                                                    </div>
                                                </div>
                                                <p class="switch_text">@lang('menu.manufacturing_report')</p>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <p class="sub-menu-group-title">{{ __("Task Management") }}</p>
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
                                        <p class="switch_text">@lang('menu.sms_settings')</p>
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
