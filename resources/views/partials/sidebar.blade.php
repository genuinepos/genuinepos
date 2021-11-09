<div id="primary_nav" class="g_blue toggle-leftbar">
    <div class="first__left">
        <div class="main__nav">
            <ul id="" class="">
                <li data-menu="dashboardmenu" class="">
                    <a href="{{ route('dashboard.dashboard') }}" class="">
                        <img src="{{ asset('public/backend/asset/img/icon/pie-chart.svg') }}" alt="">
                        <p class="title">Dashboard</p>
                    </a>
                </li>

                <li data-menu="product" class="{{ request()->is('product*') ? 'menu_active' : '' }}">
                    <a href="#">
                        <img src="{{ asset('public/backend/asset/img/icon/package.svg') }}" alt="">
                        <p class="title">@lang('menu.product')</p>
                    </a>
                </li>

                @if (json_decode($generalSettings->modules, true)['contacts'] == '1')
                    @if (auth()->user()->permission->supplier['supplier_all'] == '1' || auth()->user()->permission->customers['customer_all'] == '1')
                        <li data-menu="contact" class="{{ request()->is('contacts*') ? 'menu_active' : '' }}">
                            <a href="#" class=""><img src="{{ asset('public/backend/asset/img/icon/agenda.svg') }}">
                                <p class="title">@lang('menu.contacts')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (json_decode($generalSettings->modules, true)['purchases'] == '1')
                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)

                        <li data-menu="purchases" class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                            <a href="#" class="">
                                <img src="{{ asset('public/backend/asset/img/icon/bill.svg') }}">
                                <p class="title">@lang('menu.purchases')</p>
                            </a>
                        </li>
                    @else
                        @if (auth()->user()->branch_id && auth()->user()->branch->purchase_permission == 1)
                            @if (auth()->user()->permission->purchase['purchase_all'] == '1')

                                <li data-menu="purchases"
                                    class="{{ request()->is('purchases*') ? 'menu_active' : '' }}">
                                    <a href="#" class="">
                                        <img src="{{ asset('public/backend/asset/img/icon/bill.svg') }}">
                                        <p class="title">@lang('menu.purchases')</p>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif
                @endif

                @if (auth()->user()->permission->sale['pos_all'] == '1' || auth()->user()->permission->sale['sale_access'] == '1')
                    <li data-menu="sales" class="{{ request()->is('sales*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/shopping-bag.svg') }}">
                            <p class="title">@lang('menu.sales')</p>
                        </a>
                    </li>
                @endif

                @if ($warehouseCount > 0)
                    @if (json_decode($generalSettings->modules, true)['transfer_stock'] == '1')
                        <li data-menu="transfer" class="{{ request()->is('transfer/stocks*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('public/backend/asset/img/icon/transfer.svg') }}">
                                <p class="title">@lang('menu.transfer')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (json_decode($generalSettings->modules, true)['stock_adjustment'] == '1')
                    @if (auth()->user()->permission->s_adjust['adjustment_all'] == '1')
                        <li data-menu="adjustment"
                            class="{{ request()->is('stock/adjustments*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('public/backend/asset/img/icon/slider-tool.svg') }}">
                                <p class="title">@lang('menu.adjustment')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (json_decode($generalSettings->modules, true)['expenses'] == '1')
                    <li data-menu="expenses" class="{{ request()->is('expenses*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/budget.svg') }}">
                            <p class="title">@lang('menu.expenses')</p>
                        </a>
                    </li>
                @endif

                @if (json_decode($generalSettings->modules, true)['accounting'] == '1')
                    @if (auth()->user()->permission->accounting['ac_access'] == '1')
                        <li data-menu="accounting" class="{{ request()->is('accounting*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('public/backend/asset/img/icon/accounting.svg') }}">
                                <p class="title">@lang('menu.accounting')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (auth()->user()->permission->user['user_view'] == '1')
                    <li data-menu="users" class="{{ request()->is('users*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/team.svg') }}">
                            <p class="title">@lang('menu.users')</p>
                        </a>
                    </li>
                @endif

                @if ($addons->hrm)
                    <li data-menu="hrm" class="{{ request()->is('hrm*') ? 'menu_active' : '' }}">
                        <a href="{{ route('hrm.dashboard.index') }}">
                            <img src="{{ asset('public/backend/asset/img/icon/human-resources.svg') }}">
                            <p class="title">@lang('menu.hrm')</p>
                        </a>
                    </li>
                @endif

                @if ($addons->manufacturing == 1)
                    {{-- @if (json_decode($generalSettings->modules, true)['manufacturing'] == '1') --}}
                    @if (auth()->user()->permission->manufacturing['menuf_view'] == '1')
                        <li data-menu="manufacture" class="{{ request()->is('manufacturing*') ? 'menu_active' : '' }}">
                            <a href="#">
                                <img src="{{ asset('public/backend/asset/img/icon/conveyor.svg') }}">
                                <p class="title">Manufacture</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($addons->todo == 1)
                    @if (json_decode($generalSettings->modules, true)['requisite'] == '1')
                        <li class="{{ request()->is('essentials*') ? 'menu_active' : '' }}">
                            <a href="{{ route('workspace.index') }}">
                                <img src="{{ asset('public/backend/asset/img/icon/to-do-list.svg') }}">
                                <p class="title">@lang('menu.essentials')</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if ($addons->service == 1)
                    <li class="">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/service.svg') }}">
                            <p class="title">@lang('menu.service')</p>
                        </a>
                    </li>
                @endif

                @if ($addons->e_commerce == 1)
                    <li class="">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/ecommerce2.svg') }}">
                            <p class="title">@lang('menu.e_commerce')</p>
                        </a>
                    </li>
                @endif

                <li data-menu="reports" class="{{ request()->is('reports*') ? 'menu_active' : '' }}">
                    <a href="#">
                        <img src="{{ asset('public/backend/asset/img/icon/business-report.svg') }}">
                        <p class="title">Reports</p>
                    </a>
                </li>

                @if (auth()->user()->permission->setup['branch'] == '1' || auth()->user()->permission->setup['warehouse'] == '1' || auth()->user()->permission->setup['tax'] == '1' || auth()->user()->permission->setup['g_settings'] == '1' || auth()->user()->permission->setup['p_settings'] == '1' || auth()->user()->permission->setup['inv_sc'] == '1' || auth()->user()->permission->setup['inv_lay'] == '1' || auth()->user()->permission->setup['barcode_settings'] == '1' || auth()->user()->permission->setup['cash_counters'] == '1')
                    <li data-menu="settings" class="{{ request()->is('settings*') ? 'menu_active' : '' }}">
                        <a href="#">
                            <img src="{{ asset('public/backend/asset/img/icon/settings.svg') }}">
                            <p class="title">@lang('menu.setup')</p>
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
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>Product Management</strong></p>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->product['product_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('products.add.view') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-plus-circle"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_product')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['product_all'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('products.all.product') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-sitemap"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.product_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->product['product_add'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.import.create') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-file-import"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.import_products')</p>
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            @if (auth()->user()->permission->category['category_all'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.categories.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-th-large"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.categories')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->brand['brand_all'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('product.brands.index') }}" class="bar-link">
                                            <span>
                                                <i class="fas fa-band-aid"></i>
                                            </span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.brand')</p>
                                </div>
                            @endif

                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('settings.units.index') }}" class="bar-link">
                                        <span><i class="fas fa-weight-hanging"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.units')</p>
                            </div>

                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('product.variants.index') }}" class="bar-link">
                                        <span>
                                            <i class="fas fa-align-center"></i>
                                        </span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.variants')</p>
                            </div>

                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('product.warranties.index') }}" class="bar-link">
                                        <span>
                                            <i class="fas fa-shield-alt"></i>
                                        </span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.warranties')</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('product.selling.price.groups.index') }}" class="bar-link">
                                        <span>
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.selling_price_group')</p>
                            </div>

                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('barcode.index') }}" class="bar-link">
                                        <span>
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.generate_barcode')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (json_decode($generalSettings->modules, true)['contacts'] == '1')
                @if (auth()->user()->permission->supplier['supplier_all'] == '1' || auth()->user()->permission->customers['customer_all'] == '1')
                    <div class="sub-menu_t" id="contact">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Contact Management</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    @if (auth()->user()->permission->supplier['supplier_all'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('contacts.supplier.index') }}" class="bar-link">
                                                    <span><i class="fas fa-address-card"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.suppliers')</p>
                                        </div>
                                    @endif

                                    @if (auth()->user()->permission->customers['customer_all'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('contacts.customer.index') }}" class="bar-link">
                                                    <span><i class="far fa-address-card"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.customers') </p>
                                        </div>

                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('contacts.customers.groups.index') }}" class="bar-link">
                                                    <span><i class="fas fa-users"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.customer_groups')</p>
                                        </div>
                                    @endif
                                </div>
                                <hr>

                                <div class="row">
                                    @if (auth()->user()->permission->supplier['supplier_all'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('contacts.suppliers.import.create') }}" class="bar-link">
                                                    <span><i class="fas fa-file-import"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.import_suppliers')</p>
                                        </div>
                                    @endif

                                    @if (auth()->user()->permission->customers['customer_all'] == '1')
                                        <div
                                            class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('contacts.customers.import.create') }}" class="bar-link">
                                                    <span><i class="fas fa-file-upload"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.import_customers')</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (json_decode($generalSettings->modules, true)['purchases'] == '1')
                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                    <div class="sub-menu_t" id="purchases">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Purchase Management</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.create') }}" class="bar-link">
                                                <span><i class="fas fa-shopping-cart"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_purchase')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.index_v2') }}" class="bar-link">
                                                <span><i class="fas fa-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.purchase_list')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.product.list') }}" class="bar-link">
                                                <span><i class="fas fa-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.purchase_product_list')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.po.list') }}" class="bar-link">
                                                <span><i class="fas fa-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.po_list')</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.returns.supplier.return') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-plus-circle"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text"> @lang('menu.add_return')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('purchases.returns.index') }}" class="bar-link">
                                                <span><i class="fas fa-undo"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.purchase_return_list')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    @if (auth()->user()->branch_id && auth()->user()->branch->purchase_permission == 1)
                        @if (auth()->user()->permission->purchase['purchase_all'] == '1')
                            <div class="sub-menu_t" id="purchases">
                                <div class="sub-menu-width">
                                    <div class="model__close bg-secondary-2">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <p class="text-muted float-start mt-1"><strong>Purchase Management</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid">
                                        <div class="row">
                                            @if (auth()->user()->permission->purchase['purchase_add'] == '1')
                                                <div
                                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('purchases.create') }}" class="bar-link">
                                                            <span><i class="fas fa-shopping-cart"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text">@lang('menu.add_purchase')</p>
                                                </div>
                                            @endif

                                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.index_v2') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_list')</p>
                                            </div>

                                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.product.list') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.purchase_product_list')</p>
                                            </div>

                                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                <div class="switch_bar">
                                                    <a href="{{ route('purchases.po.list') }}" class="bar-link">
                                                        <span><i class="fas fa-list"></i></span>
                                                    </a>
                                                </div>
                                                <p class="switch_text">@lang('menu.po_list')</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            @if (auth()->user()->permission->purchase['purchase_return'] == '1')
                                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        <a href="{{ route('purchases.returns.supplier.return') }}"
                                                            class="bar-link">
                                                            <span><i class="fas fa-plus-circle"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text"> @lang('menu.add_return')</p>
                                                </div>

                                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                                    <div class="switch_bar">
                                                        {{-- <span class="notify-grin">30</span> --}}
                                                        <a href="{{ route('purchases.returns.index') }}"
                                                            class="bar-link">
                                                            <span><i class="fas fa-undo"></i></span>
                                                        </a>
                                                    </div>
                                                    <p class="switch_text"> @lang('menu.purchase_return_list')</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            @endif

            <div class="sub-menu_t" id="sales">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>Sale Management</strong></p>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->sale['sale_access'] == '1')
                                @if (json_decode($generalSettings->modules, true)['add_sale'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.create') }}" class="bar-link">
                                                <span><i class="fas fa-cart-plus"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text"> @lang('menu.add_sale')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.index2') }}" class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_sale_list')</p>
                                    </div>
                                @endif
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.product.list') }}" class="bar-link">
                                            <span><i class="fas fa-tasks"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sold_product_list')</p>
                                </div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            @if (json_decode($generalSettings->modules, true)['pos'] == '1')
                                @if (auth()->user()->permission->sale['pos_all'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.pos.create') }}" class="bar-link">
                                                <span><i class="fas fa-cash-register"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.pos')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('sales.pos.list') }}" class="bar-link">
                                                <span><i class="fas fa-tasks"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.pos_sale_list')</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            @if (auth()->user()->permission->sale['sale_draft'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.drafts') }}" class="bar-link">
                                            <span><i class="fas fa-drafting-compass"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.draft_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['sale_quotation'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.quotations') }}" class="bar-link">
                                            <span><i class="fas fa-quote-right"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.quotation_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['return_access'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.returns.index') }}" class="bar-link">
                                            <span><i class="fas fa-undo"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sale_return_list')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->sale['shipment_access'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('sales.shipments') }}" class="bar-link">
                                            <span><i class="fas fa-shipping-fast"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.shipments')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if ($warehouseCount > 0)
                @if (json_decode($generalSettings->modules, true)['transfer_stock'] == '1')
                    <div class="sub-menu_t" id="transfer">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Stock Transfer Management</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                        <p>{!! __('menu.transfer_stock_heading_1') !!}</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.to.branch.create') }}"
                                                class="bar-link"> <span><i class="fas fa-exchange-alt"></i></span>
                                            </a>
                                        </div>

                                        <p class="switch_text">@lang('menu.add_transfer')
                                            <small class="ml-1"><b>(@lang('menu.to_branch'))</small></b>
                                        </p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.to.branch.index') }}" class="bar-link">
                                                <span><i class="fas fa-list-ul"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.transfer_list')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-check-double"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.receive_stocks')
                                            <small class="ml-1"><b>(From B.Location)</small></b>
                                        </p>
                                    </div>
                                </div>

                                <hr>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-10 p-1 ms-4 text-center d-flex justify-content-top align-items-start flex-column">
                                        <p>{!! __('menu.transfer_stock_heading_2') !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.to.warehouse.create') }}" class="bar-link">
                                                <span><i class="fas fa-exchange-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_transfer')
                                            <small class="ml-1">(@lang('menu.to_warehouse'))</small></p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stock.to.warehouse.index') }}" class="bar-link">
                                                <span><i class="fas fa-list-ul"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.transfer_list') <small class="ml-1">
                                                (@lang('menu.to_warehouse'))</small>
                                        </p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('transfer.stocks.to.branch.receive.stock.index') }}"
                                                class="bar-link">
                                                <span><i class="fas fa-check-double"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.receive_stocks')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (json_decode($generalSettings->modules, true)['stock_adjustment'] == '1')
                @if (auth()->user()->permission->s_adjust['adjustment_all'] == '1')
                    <div class="sub-menu_t" id="adjustment">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Stock Adjustment</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    @if (auth()->user()->permission->s_adjust['adjustment_add'] == '1')
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('stock.adjustments.create') }}" class="bar-link">
                                                    <span><i class="fas fa-plus-square"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_stock_adjustment_from_branch')</p>
                                        </div>

                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('stock.adjustments.create.from.warehouse') }}" class="bar-link">
                                                    <span><i class="fas fa-plus-square"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.add_stock_adjustment_from_warehouse')</p>
                                        </div>
                                    @endif

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('stock.adjustments.index') }}" class="bar-link">
                                                <span><i class="fas fa-th-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.stock_adjustment_list')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (json_decode($generalSettings->modules, true)['expenses'] == '1')
                <div class="sub-menu_t" id="expenses">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>Expense Management</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('expanses.create') }}" class="bar-link">
                                            <span><i class="fas fa-plus-square"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.add_expense')</p>
                                </div>

                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('expanses.categories.index') }}" class="bar-link">
                                            <span><i class="fas fa-cubes"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.expense_categories')</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('expanses.index') }}" class="bar-link">
                                            <span><i class="far fa-list-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.expense_list')</p>
                                </div>

                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('expanses.category.wise.expense') }}" class="bar-link">
                                            <span><i class="far fa-list-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.category_wise_expenses')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (json_decode($generalSettings->modules, true)['accounting'] == '1')
                @if (auth()->user()->permission->accounting['ac_access'] == 1)
                    <div class="sub-menu_t" id="accounting">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Account Management</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.banks.index') }}" class="bar-link">
                                                <span><i class="fas fa-university"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.bank')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.types.index') }}" class="bar-link">
                                                <span><i class="fas fa-th"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.account_types')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.accounts.index') }}" class="bar-link">
                                                <span><i class="fas fa-money-check-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.accounts')</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.assets.index') }}" class="bar-link">
                                                <span><i class="fas fa-luggage-cart"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.assets')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.loan.index') }}" class="bar-link">
                                                <span><i class="fas fa-hand-holding-usd"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.loans')</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.balance.sheet') }}" class="bar-link">
                                                <span><i class="fas fa-balance-scale"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.balance_sheet')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.trial.balance') }}" class="bar-link">
                                                <span><i class="fas fa-balance-scale-right"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.trial_balance')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('accounting.cash.flow') }}" class="bar-link">
                                                <span><i class="fas fa-money-bill-wave"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.cash_flow')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (auth()->user()->permission->user['user_view'] == '1')
                <div class="sub-menu_t" id="users">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>User Management</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->user['user_view'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('users.create') }}" class="bar-link">
                                                <span><i class="fas fa-user-plus"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_user')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->user['user_view'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('users.index') }}" class="bar-link">
                                                <span><i class="fas fa-list-ol"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.user_list')</p>
                                    </div>
                                @endif
                            </div>
                            <hr>
                            <div class="row">
                                @if (auth()->user()->permission->roles['role_add'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('users.role.create') }}" class="bar-link">
                                                <span><i class="fas fa-plus-circle"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.add_role')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->roles['role_view'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('users.role.index') }}" class="bar-link">
                                                <span><i class="fas fa-th-list"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.role_list')</p>
                                    </div>
                                @endif
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            @endif

            @if (auth()->user()->permission->setup['branch'] == '1' || auth()->user()->permission->setup['warehouse'] == '1' || auth()->user()->permission->setup['tax'] == '1' || auth()->user()->permission->setup['g_settings'] == '1' || auth()->user()->permission->setup['p_settings'] == '1' || auth()->user()->permission->setup['inv_sc'] == '1' || auth()->user()->permission->setup['inv_lay'] == '1' || auth()->user()->permission->setup['barcode_settings'] == '1' || auth()->user()->permission->setup['cash_counters'] == '1')
                <div class="sub-menu_t" id="settings">
                    <div class="sub-menu-width">
                        <div class="model__close bg-secondary-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-muted float-start mt-1"><strong>Settings</strong></p>
                                </div>
                                <div class="col-md-4">
                                    <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row">
                                @if (auth()->user()->permission->setup['g_settings'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.general.index') }}" class="bar-link">
                                                <span><i class="fas fa-cogs"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.general_settings')</p>
                                    </div>
                                @endif

                                @if ($addons->branches == 1)
                                    @if (auth()->user()->permission->setup['branch'] == '1')
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                            <div class="switch_bar">
                                                <a href="{{ route('settings.branches.index') }}" class="bar-link">
                                                    <span><i class="fas fa-project-diagram"></i></span>
                                                </a>
                                            </div>
                                            <p class="switch_text">@lang('menu.branches')</p>
                                        </div>
                                    @endif
                                @endif

                                @if (auth()->user()->permission->setup['warehouse'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.warehouses.index') }}" class="bar-link">
                                                <span><i class="fas fa-warehouse"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.warehouses') </p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['tax'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.taxes.index') }}" class="bar-link">
                                                <span><i class="fas fa-percentage"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.taxes')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['p_settings'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.payment.card.types.index') }}" class="bar-link">
                                                <span><i class="fas fa-credit-card"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.payment_settings')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['inv_sc'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('invoices.schemas.index') }}" class="bar-link">
                                                <span><i class="fas fa-file-invoice-dollar"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.invoice_schema')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['inv_lay'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('invoices.layouts.index') }}" class="bar-link">
                                                <span><i class="fas fa-file-invoice"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.invoice_layout')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['barcode_settings'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.barcode.index') }}" class="bar-link">
                                                <span><i class="fas fa-barcode"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.barcode_settings')</p>
                                    </div>
                                @endif

                                @if (auth()->user()->permission->setup['cash_counters'] == '1')
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('settings.cash.counter.index') }}" class="bar-link">
                                                <span><i class="fas fa-store"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.cash_counter')</p>
                                    </div>
                                @endif

                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('settings.release.note.index') }}" class="bar-link">
                                            <span><i class="far fa-arrow-alt-circle-up"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">Version Release Notes</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($addons->manufacturing == 1)
                @if (auth()->user()->permission->manufacturing['menuf_view'] == '1')
                    <div class="sub-menu_t" id="manufacture">
                        <div class="sub-menu-width">
                            <div class="model__close bg-secondary-2">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted float-start mt-1"><strong>Manufacturing</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.process.index') }}" class="bar-link">
                                                <span><i class="fas fa-dumpster-fire"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">Process</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.productions.index') }}" class="bar-link">
                                                <span><i class="fas fa-shapes"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">Production</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('manufacturing.settings.index') }}" class="bar-link">
                                                <span><i class="fas fa-sliders-h"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">Settings</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column flex-column">
                                        <div class="switch_bar">
                                            <a href="" class="bar-link">
                                                <span><i class="fas fa-file-alt"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">Manufacturing Report</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            <div class="sub-menu_t" id="reports">
                <div class="sub-menu-width">
                    <div class="model__close bg-secondary-2">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted float-start mt-1"><strong>All Report</strong></p>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn text-white btn-sm btn-info close-model float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            @if (auth()->user()->permission->report['loss_profit_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.profit.loss.index') }}" class="bar-link">
                                            <span><i class="fas fa-chart-line"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.profit_loss')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['purchase_sale_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.sales.purchases.index') }}" class="bar-link">
                                            <span><i class="far fa-chart-bar"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.purchase_sale')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['tax_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.taxes.index') }}" class="bar-link">
                                            <span><i class="fas fa-percent"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.tax_report')</p>
                                </div>
                            @endif

                            @if (json_decode($generalSettings->modules, true)['contacts'] == '1')
                                @if (auth()->user()->permission->report['cus_sup_report'] == '1')
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.supplier.index') }}" class="bar-link">
                                                <span><i class="fas fa-id-card"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.supplier_report')</p>
                                    </div>

                                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                        <div class="switch_bar">
                                            <a href="{{ route('reports.customer.index') }}" class="bar-link">
                                                <span><i class="far fa-id-card"></i></span>
                                            </a>
                                        </div>
                                        <p class="switch_text">@lang('menu.customer_report')</p>
                                    </div>
                                @endif
                            @endif

                            @if (auth()->user()->permission->report['stock_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.stock.index') }}" class="bar-link">
                                            <span><i class="fas fa-sitemap"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.stock_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['stock_adjustment_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.stock.adjustments.index') }}" class="bar-link">
                                            <span><i class="fas fa-sliders-h"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.stock_adjustment_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['pro_purchase_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.product.purchases.index') }}" class="bar-link">
                                            <span><i class="fas fa-shopping-cart"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.product_purchase_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['pro_sale_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.product.sales.index') }}" class="bar-link">
                                            <span><i class="fas fa-cart-arrow-down"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.product_sale_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['purchase_payment_report'] == '1')
                                <div
                                    class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.purchase.payments.index') }}" class="bar-link">
                                            <span><i class="fas fa-money-check-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.purchase_payment_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['sale_payment_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.sale.payments.index') }}" class="bar-link">
                                            <span><i class="fas fa-hand-holding-usd"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sale_payment_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['register_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.cash.registers.index') }}" class="bar-link">
                                            <span><i class="fas fa-cash-register"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.register_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['representative_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.sale.representive.index') }}" class="bar-link">
                                            <span><i class="fas fa-user-tie"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.sales_representative_report')</p>
                                </div>
                            @endif

                            @if (auth()->user()->permission->report['expanse_report'] == '1')
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.expenses.index') }}" class="bar-link">
                                            <span><i class="far fa-money-bill-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.expense_report')</p>
                                </div>
                            @endif

                            <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                <div class="switch_bar">
                                    <a href="{{ route('reports.financial.index') }}" class="bar-link">
                                        <span><i class="fas fa-money-bill-wave"></i></span>
                                    </a>
                                </div>
                                <p class="switch_text">@lang('menu.financial_report')</p>
                            </div>

                            @if ($addons->hrm == 1)
                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.payroll') }}" class="bar-link">
                                            <span><i class="fas fa-money-bill-alt"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.payroll_report')</p>
                                </div>

                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.payroll.payment') }}" class="bar-link">
                                            <span><i class="fas fa-handshake"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.payroll_payment_report')</p>
                                </div>

                                <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1 ms-4 text-center d-flex justify-content-top align-items-center flex-column">
                                    <div class="switch_bar">
                                        <a href="{{ route('reports.attendance') }}" class="bar-link">
                                            <span><i class="fas fa-paste"></i></span>
                                        </a>
                                    </div>
                                    <p class="switch_text">@lang('menu.attendance_report')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
