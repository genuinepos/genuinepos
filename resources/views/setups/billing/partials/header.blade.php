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
                            {{ $heading }}
                        </p>
                    </div>

                    @if ($generalSettings['subscription']->is_trial_plan == 1 || ($generalSettings['subscription']->has_due_amount == 0 && $generalSettings['subscription']->due_repayment_date))
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
                                <a href="{{ route('software.service.billing.upgrade.plan.index') }}" class="btn btn-sm btn-danger">{{ __('Upgrade Plan') }}</a>
                            </p>
                        @elseif ($generalSettings['subscription']->has_due_amount == 0 && $generalSettings['subscription']->due_repayment_date)
                            @php
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                            @endphp

                            <p class="text-white mt-1">
                                {{ __('Due Repayment Date') }} : <span class="text-danger">{{ date($dateFormat, strtotime($generalSettings['subscription']->due_repayment_date)) }}</span>
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
                                @if (date('Y-m-d') > $branchExpireDate)

                                    <span class="text-white">{{ __('Shop') }} | {{ __('Expired On') }}</span> : <span class="text-danger">{{ $__branchExpireDate }}</span>
                                @else

                                    <span class="text-white">{{ __('Shop') }} | {{ __('Expire On') }}</span> : <span class="text-success">{{ $__branchExpireDate }}</span>
                                @endif
                            </p>
                        @else
                            @php
                                $dateFormat = $generalSettings['business_or_shop__date_format'];
                                $businessExpireDate = $generalSettings['subscription']->business_expire_date;
                                $__businessExpireDate = date($dateFormat, strtotime($businessExpireDate));
                            @endphp
                            <p class="text-white mt-1">
                                @if (date('Y-m-d') > $businessExpireDate)

                                    <span class="text-white">{{ __("Business | Expired On") }}</span> : <span class="text-danger">{{ $__businessExpireDate }}</span>
                                @else

                                    <span class="text-white">{{ __("Business | Expire On") }}</span> : <span class="text-success">{{ $__businessExpireDate }}</span>
                                @endif
                            </p>
                        @endif
                    @endif

                    <div class="head__content__sec">
                        <ul class="head__cn">
                            <li class="dp__top top-icon">
                                <a href="{{ url()->previous() }}" class="nav-btn" title="Back">
                                    <span>
                                        <i class="fas fa-long-arrow-alt-left text-white"></i>
                                        <br>
                                        {{ __("Back") }}
                                    </span>
                                </a>
                            </li>

                            <li class="top-icon d-hide d-md-block">
                                <a href="https://help.genuinepos.com/" class="nav-btn" target="_blank"><span><i class="far fa-question-circle"></i><br>{{ __('Help') }}</span></a>
                            </li>
                            <li class="dp__top top-icon" style="cursor: pointer;">
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
