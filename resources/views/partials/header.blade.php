<header>
    <div class="navigation red_linear_bg">
        <div class="panel__nav">
            <div class=" top-menu">
                <div class="logo__sec">
                    @if (config('speeddigit.dynamic_app_logo') == true)
                        <a href="{{ route('dashboard.index') }}" class="logo">
                            @if ($generalSettings['business_or_shop__business_logo'])
                                <img style="height: height; width:auto;" src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="System Logo" class="logo__img">
                            @else
                                <h6 class="text-white fw-bold text-uppercase">{{ $generalSettings['business_or_shop__business_name'] }}</h6>
                            @endif
                        </a>
                    @else
                        <img style="height: height; width:auto;" src="{{ asset(config('speeddigit.app_logo')) }}" alt="System Logo" class="logo__img">
                    @endif
                </div>
                <div id="left_bar_toggle"><span class="fa-light fa-bars"></span></div>
                <div class="notify-menu">
                    <div class="company-name">
                        <p class="text-uppercase">
                            @if (auth()?->user()?->branch?->parent_branch_id)

                                {{ auth()?->user()?->branch?->parentBranch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }} <small class="fw-bold text-danger" style="font-size:10px!important;">({{ __('Chain Store') }})</small>
                            @else
                                @if (auth()?->user()?->branch)
                                    {{ auth()?->user()?->branch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                                    @if (count(auth()?->user()?->branch?->childBranches) > 0)
                                        <small class="fw-bold text-danger" style="font-size:10px!important;">({{ __('Main Store') }})</small>
                                    @else
                                        <small class="fw-bold text-danger" style="font-size:10px!important;">({{ __('DIFFERENT Store') }})</small>
                                    @endif
                                @else
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </p>
                        <span><strong>FY :</strong> {{ $generalSettings['business_or_shop__financial_year'] }}</span>
                    </div>

                    <div class="head__content__sec">
                        <ul class="head__cn">
                            @if (auth()->user()->can('product_add') || auth()->user()->can('sales_create_by_add_sale') || auth()->user()->can('create_sales_return') || auth()->user()->can('purchase_add') || auth()->user()->can('purchase_return_add') || auth()->user()->can('transfer_stock_create') || auth()->user()->can('stock_adjustment_add') || auth()->user()->can('production_add') || auth()->user()->can('user_add') || auth()->user()->can('role_add'))

                                <li class="top-icon d-hide d-md-block">
                                    <a class="nav-btn create-btn" type="button" data-bs-toggle="dropdown">
                                        <span>
                                            <i class="fa fa-plus"></i>
                                            <br>{{ __('Quick Add') }}</span>
                                        </span>
                                    </a>

                                    <ul class="dropdown-menu short_create_btn_list">
                                        <li><span class="d-block fw-500 px-2 pb-1 fz-14">{{ __('Quick Add') }}</span></li>
                                        <hr class="m-0">

                                        @if (auth()->user()->can('product_add'))
                                            <li><a href="{{ route('products.create') }}" class="dropdown-item text-dark">{{ __('Add Product') }}</a></li>
                                        @endif
                                        {{-- <li><a href="#" class="dropdown-item text-dark">{{ __('Product Pricing/Costing') }}</a></li> --}}

                                        @if (auth()->user()->can('sales_create_by_add_sale'))
                                            <li><a href="{{ route('sales.create') }}" class="dropdown-item text-dark">{{ __('Add Sale') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('create_sales_return'))
                                            <li><a href="{{ route('sales.returns.create') }}" class="dropdown-item text-dark">{{ __('Add Sales Return') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('purchase_add'))
                                            <li><a href="{{ route('purchases.create') }}" class="dropdown-item text-dark">{{ __('Add Purchase') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('purchase_return_add'))
                                            <li><a href="{{ route('purchase.returns.create') }}" class="dropdown-item text-dark">{{ __('Add Purchase Return') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('transfer_stock_create'))
                                            <li><a href="{{ route('transfer.stocks.create') }}" class="dropdown-item text-dark">{{ __('Add Transfer Stock') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('stock_adjustment_add'))
                                            <li><a href="{{ route('stock.adjustments.create') }}" class="dropdown-item text-dark">{{ __('Add Stock Adjustment') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('production_add'))
                                            <li><a href="{{ route('manufacturing.productions.create') }}" class="dropdown-item text-dark">{{ __('Add Production') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('user_add'))
                                            <li><a href="{{ route('users.create') }}" class="dropdown-item text-dark">{{ __('Add User') }}</a></li>
                                        @endif

                                        @if (auth()->user()->can('user_add'))
                                            <li><a href="{{ route('users.role.create') }}" class="dropdown-item text-dark">{{ __('Add Role') }}</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            <li class="top-icon d-hide d-md-block" id="hard_reload">
                                <a href="#" class="nav-btn" title="Reload"><span><i class="fas fa-redo-alt"></i><br>{{ __('Reload') }}</span></a>
                            </li>

                            {{-- <li class="top-icon d-hide d-md-block"><a href="#" target="_blank"><b><span class="fas fa-globe"></span></b></a></li> --}}

                            {{-- @if (auth()->user()->can('communication'))
                                <li class="top-icon d-hide d-md-block" id="get_mail" title="Communicate"><a href="#"><b><i class="fas fa-th-large"></i></b></a>
                                    <ul class="lists">
                                        <li><a href="#"><i class="fas fa-bell"></i><span class="title">Notice Board</span></a></li>
                                        <li><a href="#"><i class="fas fa-envelope-open"></i><span class="title">Send Email</span></a></li>
                                        <li><a href="#"><i class="fas fa-comment-alt"></i><span class="title">Send SMS</span></a></li>
                                        <li><a href="#"><i class="fas fa-download"></i><span class="title">Download {{ __('Center') }}</span></a></li>
                                    </ul>
                                </li>
                            @endif --}}

                            @if (auth()->user()->can('today_summery'))
                                <li class="top-icon"><a href="#" class="nav-btn" id="todaySummeryBtn"><span><i class="far fa-calendar"></i><br>{{ __('Today') }}</span></a></li>
                            @endif

                            <li class="top-icon dropdown notification-dropdown">
                                <a href="" class="nav-btn" id="dropdownMenuButton0" data-bs-toggle="dropdown">
                                    <span><i class="far fa-bell"></i><br>{{ __('Notification') }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton0">
                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 1 <span>{{ __('3 Days ago') }}</span></a>
                                    </li>

                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 1 <span>{{ __('3 Days ago') }}</span></a>
                                    </li>

                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 1 <span>{{ __('3 Days ago') }}</span></a>
                                    </li>

                                    <a href="#" class="btn btn-sm btn-primary">{{ __('View All') }}</a>
                                </ul>
                            </li>

                            @if ($generalSettings['modules__pos'] == '1' && auth()->user()->can('sales_create_by_pos'))
                                <li class="top-icon"><a href="{{ route('sales.pos.create') }}" class="nav-btn"><span><i class="fas fa-cash-register"></i><br>{{ __('POS') }}</span></a></li>
                            @endif

                            <li class="top-icon">
                                <a href="" class="nav-btn" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                                    <span>
                                        <i class="fas fa-calculator"></i>
                                        <br>
                                        {{ __('Calculator') }}
                                    </span>
                                </a>
                                <div class="modal" id="calculatorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modail-body" id="calculator">
                                                <div class="calculator-bg">
                                                    <div class="calculator-bg__main">
                                                        <div class="calculator-bg__main__screen">
                                                            <div class="calculator-bg__main__screen__first"></div>
                                                            <div class="calculator-bg__main__screen__second">0</div>
                                                        </div>
                                                        <button class="calculator-bg__main__ac">AC</button>
                                                        <button class="calculator-bg__main__del">DEL</button>
                                                        <button class="calculator-bg__main__operator">/</button>
                                                        <button class="calculator-bg__main__num">7</button>
                                                        <button class="calculator-bg__main__num">8</button>
                                                        <button class="calculator-bg__main__num">9</button>
                                                        <button class="calculator-bg__main__operator">x</button>
                                                        <button class="calculator-bg__main__num">4</button>
                                                        <button class="calculator-bg__main__num">5</button>
                                                        <button class="calculator-bg__main__num">6</button>
                                                        <button class="calculator-bg__main__operator">+</button>
                                                        <button class="calculator-bg__main__num">1</button>
                                                        <button class="calculator-bg__main__num">2</button>
                                                        <button class="calculator-bg__main__num">3</button>
                                                        <button class="calculator-bg__main__operator">-</button>
                                                        <button class="calculator-bg__main__num decimal">.</button>
                                                        <button class="calculator-bg__main__num">0</button>
                                                        <button class="calculator-bg__main__result">=</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown dp__top top-icon">
                                <a href="" class="nav-btn" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                    <span>
                                        <i class="fas fa-language"></i>
                                        <br>
                                        {{ __('Language') }}
                                    </span>
                                </a>

                                <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a style="display:inline;" class="dropdown-item {{ app()->isLocale('en') ? 'text-success' : '' }}" href="{{ route('change.lang', 'en') }}">English</a>
                                    </li>

                                    <li>
                                        <a style="display:inline;" class="dropdown-item {{ app()->isLocale('bn') ? 'text-success' : '' }}" href="{{ route('change.lang', 'bn') }}">Bangla</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="top-icon d-hide d-md-block">
                                <a href="https://help.genuinepos.com/" class="nav-btn" target="_blank"><span><i class="far fa-question-circle"></i><br>{{ __('Help') }}</span></a>
                            </li>
                            <li class="dp__top top-icon">
                                <a role="button" class="nav-btn" id="openRightSidebar" title="User">
                                    <span>
                                        <i class="fas fa-user"></i>
                                        <br>
                                        {{ __('User') }}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
{{-- <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script> --}}
