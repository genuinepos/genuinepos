<header class="startup-form-header">
    <div class="navigation red_linear_bg">
        <div class="panel__nav">
            <div class=" top-menu">
                <div class="logo__sec">
                    <a href="#" class="logo">
                        <img style="height: height; width:auto;" src="{{ asset('assets/images/app_logo.png') }}" alt="System Logo" class="logo__img">
                    </a>
                </div>
                <div class="notify-menu">
                    <div class="company-name">
                        <p class="text-uppercase">
                            {{ __('Startup') }}
                        </p>
                    </div>

                    @if ($generalSettings['subscription']->is_trial_plan == 1 || ($generalSettings['subscription']->initial_payment_status == 0 && $generalSettings['subscription']->initial_plan_expire_date))
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
                        @elseif ($generalSettings['subscription']->initial_payment_status == 0 && $generalSettings['subscription']->initial_plan_expire_date)
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
                                <span class="text-white">{{ __('Shop Expire On') }}</span> : <span class="text-danger">{{ $__branchExpireDate }}</span>
                            </p>
                        @endif
                    @endif

                    <div class="head__content__sec">
                        <ul class="head__cn">
                            <li class="top-icon d-hide d-md-block">
                                <a href="https://help.genuinepos.com/" class="nav-btn" target="_blank"><span><i class="far fa-question-circle"></i><br>{{ __('Help') }}</span></a>
                            </li>
                            <li class="dp__top top-icon">
                                <a class="nav-btn" id="logout_option" title="Logout">
                                    <span>
                                        <i class="fas fa-power-off"></i>
                                        <br>
                                        {{ __("Logout") }}
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
