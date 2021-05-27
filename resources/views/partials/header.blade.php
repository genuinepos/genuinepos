<header>
    <div class="navigation_t">
        <div class="panel__nav">
            <div class=" top-menu">
                <div class="logo__sec">
                    <a href="" class="logo">
                        <img src="{{asset('public/uploads/business_logo/'.json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                    </a>
                </div>
                <div id="left_bar_toggle"><span class="fas fa-bars"></span></div>
            </div>
            <div class="notify-menu">
                <div class="head__content__sec">
                    <ul class="head__cn">
                        <li class="top-icon ms-3"><a href="{{ route('sales.pos.create') }}"><b>POS</b></a></li>
                        <li class="dropdown dp__top top-icon">
                            <span class="notify">30</span>
                            <a href="" class="" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                <span class="fas fa-exclamation-circle"></span>
                            </a>

                            <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">

                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <a href="" class="btn__sub">View All</a>
                            </ul>
                        </li>

                        <li class="dropdown dp__top">
                            <span class="notify-grin">30</span>
                            <a href="" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                <span class="fas fa-envelope"></span>

                            </a>

                            <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">

                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <li>
                                    <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> Lorem Ipsum is simply dummy text</a>
                                </li>
                                <a href="" class="btn__sub">View All</a>
                            </ul>
                        </li>

                        <li class="dropdown dp__top">
                            <a href="" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                <i class="fas fa-language"></i>
                            </a>

                            <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <img style="height: 40px; width:40px; border-radius:3px;" src="https://cdn.staticaly.com/gh/hjnilsson/country-flags/master/svg/us.svg"/><a style="display:inline;" class="dropdown-item" href="{{ route('change.lang', 'en') }}">English</a>
                                </li>

                                <li>
                                    <img style="height: 40px; width:40px; border-radius:3px;" src="https://cdn.staticaly.com/gh/hjnilsson/country-flags/master/svg/bd.svg"/><a style="display:inline;" class="dropdown-item" href="{{ route('change.lang', 'bn') }}">Bangla</a>
                                </li>
                            </ul>
                        </li>
                    
                        <li class="dropdown dp__top top-icon">
                            <a href="" class="" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                <span class="fas fa-user"></span>
                            </a>

                            <ul class="dropdown-menu dropdown__main__menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <i class="fas fa-eye text-primary"></i><a class="dropdown-item d-block" href="#">View Profile</a>
                                </li>
                                <li>
                                    <i class="fas fa-edit text-primary"></i></span><a class="dropdown-item d-block" href="{{ route('users.profile.index') }}">Edit Profile</a>
                                </li>
                            </ul>
                        </li>

                        <li class="user_info me-5">
                            <span class="user_name">
                                {{ auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name }} |
                                @if (auth()->user()->role_type == 1)
                                    Super-Admin
                                @elseif(auth()->user()->role_type == 2)
                                    Admin
                                @else
                                    {{ auth()->user()->role->name }}
                                @endif
                            </span>
                            <span><a href="#">Help?</a></span>
                        </li>
                        <li class="top-icon">
                            <a href="" id="logout_option"><span class="fas fa-power-off" title="Logout"></span></a>
                        </li>

                    </ul>

                </div>

            </div>
        </div>
    </div>
</header>