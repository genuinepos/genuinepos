<header>
    <div class="navigation red_linear_bg">
        <div class="panel__nav">
            <div class=" top-menu">
                <div class="logo__sec">
                    <a href="{{ route('dashboard.index') }}" class="logo">
                        <img style="height: height; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="System Logo" class="logo__img">
                    </a>
                </div>
                <div id="left_bar_toggle"><span class="fa-light fa-bars"></span></div>
                <div class="notify-menu">
                    <div class="company-name">
                        <p class="text-uppercase">
                            @if (auth()?->user()?->branch?->parent_branch_id)

                                {{ auth()?->user()?->branch?->parentBranch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                            @else

                                @if (auth()?->user()?->branch)

                                    {{ auth()?->user()?->branch?->name . '(' . auth()?->user()?->branch?->area_name . ')' . '-(' . auth()?->user()?->branch?->branch_code . ')' }}
                                @else

                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                @endif
                            @endif
                        </p>
                        <span><strong>FY :</strong> {{ $generalSettings['business_or_shop__financial_year'] }}</span>
                    </div>

                    @if (
                        $generalSettings['subscription']->is_trial_plan == 1 ||
                        ($generalSettings['subscription']->initial_payment_status == 0 && $generalSettings['subscription']->initial_plan_expire_date)
                    )
                        @if ($generalSettings['subscription']->is_trial_plan == 1)
                            @php
                                $planStartDate = $generalSettings['subscription']->trial_start_date;
                                $trialDays = $generalSettings['subscription']->trial_days;
                                $startDate = new DateTime($planStartDate);
                                $lastDate = $startDate->modify('+ ' . $trialDays . ' days');
                                $expireDate = $lastDate->format('Y-m-d');
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                            @endphp

                            <p class="text-white mt-1">{{ __('Trial Expire on') }} :
                                <span class="text-danger">{{ date($dateFormat, strtotime($expireDate)) }}</span>
                                <a href="{{ route('software.service.billing.upgrade.plan') }}" class="btn btn-sm btn-danger">{{ __('Upgrade Plan') }}</a>
                            </p>
                        @elseif (
                            $generalSettings['subscription']->initial_payment_status == 0 &&
                            $generalSettings['subscription']->initial_plan_expire_date
                        )
                            @php
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                            @endphp

                            <p class="text-white mt-1">
                                {{ __('Due Repayment Date') }} : <span class="text-danger">{{ date($dateFormat, strtotime($generalSettings['subscription']->initial_plan_expire_date)) }}</span>
                                <a href="{{ route('software.service.billing.due.repayment') }}" class="btn btn-sm btn-danger">{{ __('Payment') }}</a>
                            </p>
                        @endif
                    @else
                        @if (auth()?->user()?->branch)
                            @php
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                                $branchExpireDate = auth()?->user()?->branch?->expire_date;
                                $__branchExpireDate = date($dateFormat, strtotime($branchExpireDate));
                            @endphp
                            <p class="text-white mt-1">
                                <span class="text-white">{{ __("Shop | Expire On") }}</span> : <span class="text-success">{{ $__branchExpireDate }}</span>
                            </p>
                        @else
                            @php
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                                $businessExpireDate = $generalSettings['subscription']->business_expire_date;
                                $__businessExpireDate = date($dateFormat, strtotime($businessExpireDate));
                            @endphp
                            <p class="text-white mt-1">
                                <span class="text-white">{{ __("Business | Expire On") }}</span> : <span class="text-success">{{ $__businessExpireDate }}</span>
                            </p>
                        @endif
                    @endif

                    <div class="head__content__sec">
                        <ul class="head__cn">
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
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Product') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Product Pricing/Costing') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Sale') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Sales Return') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Purchase') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Purchase Return') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Transfer Stock') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Stock Adjustment') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Production') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add User') }}</a></li>
                                    <li><a class="dropdown-item text-dark" href="#">{{ __('Add Role') }}</a></li>
                                </ul>
                            </li>

                            <li class="top-icon d-hide d-md-block" id="hard_reload">
                                <a href="#" class="nav-btn" title="Reload"><span><i class="fas fa-redo-alt"></i><br>{{ __('Reload') }}</span></a>
                            </li>
                            {{-- @if ($generalSettings['addons__e_commerce'] == 1)
                                <li class="top-icon d-hide d-md-block"><a href="#" target="_blank"><b><span class="fas fa-globe"></span></b></a></li>
                            @endif --}}

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

                                    <a href="#" class="btn btn-sm btn-primary">@lang('menu.view_all')</a>

                                </ul>
                            </li>

                            @if ($generalSettings['modules__pos'] == '1')
                                @if (auth()->user()->can('pos_add'))
                                    <li class="top-icon"><a href="{{ route('sales.pos.create') }}" class="nav-btn"><span><i class="fas fa-cash-register"></i><br>{{ __('POS') }}</span></a></li>
                                @endif
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

                                    <li>
                                        <a style="display:inline;" class="dropdown-item {{ app()->isLocale('ar') ? 'text-success' : '' }}" href="{{ route('change.lang', 'ar') }}">Arabic</a>
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
                                        User
                                    </span>
                                </a>

                                {{-- <ul class="dropdown-menu dropdown__main__menu" aria-labelledby="dropdownMenuButton2">
                                    <li>
                                        <span class="user_name text-primary">
                                            {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' .
                                            auth()->user()->last_name }}
                                            @if (auth()->user()->role_type == 1)
                                                (Super Admin)
                                            @elseif(auth()->user()->role_type == 2)
                                                (Admin)
                                            @else
                                                {{ auth()->user()->roles->first()->name }}
                                            @endif
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fas fa-eye text-primary"></i><a class="dropdown-item d-block"
                                            href="{{ route('users.profile.view', auth()->user()->id) }}">View
                                            Profile</a>
                                    </li>

                                    <li>
                                        <i class="fas fa-edit text-primary"></i></span><a class="dropdown-item d-block"
                                            href="{{ route('users.profile.index') }}">{{ __('Edit Profile') }} </a>
                                    </li>
                                </ul> --}}
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
