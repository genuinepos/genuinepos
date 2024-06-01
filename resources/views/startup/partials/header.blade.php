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
                            {{ __('Setup') }}
                        </p>
                    </div>

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
