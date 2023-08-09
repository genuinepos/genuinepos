<!-- header start -->
<div class="header">
    <div class="row g-0 align-items-center">
        <div class="col-xxl-6 col-xl-5 col-4 d-flex align-items-center gap-20">
            <div class="main-logo d-lg-block d-none">
                <div class="logo-big">
                    <a href="/">
                        <img src="{{ asset('modules/saas/images/logo_black.png') }}" alt="Logo">
                    </a>
                </div>
                <div class="logo-small">
                    <a href="/">
                        <img src="{{ asset('modules/saas/images/favicon.png') }}" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="nav-close-btn">
                <button id="navClose"><i class="fa-light fa-bars-sort"></i></button>
            </div>
            <a href="#" target="_blank" class="btn btn-sm btn-primary site-view-btn"><i class="fa-light fa-globe me-1"></i>
                <span>{{ __("Business List") }}</span>
            </a>
        </div>
        <div class="col-4 d-lg-none">
            <div class="mobile-logo">
                <a href="/">
                    <img src="{{ asset('modules/saas/images/logo_black.png') }}" alt="Logo">
                </a>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-4">
            <div class="header-right-btns d-flex justify-content-end align-items-center">
                <div class="header-collapse-group">
                    <div class="header-right-btns d-flex justify-content-end align-items-center p-0">
                        <form class="header-form">
                            <input type="search" name="search" placeholder="Search..." required>
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        <div class="header-right-btns d-flex justify-content-end align-items-center p-0">
                            {{-- <div class="lang-select">
                                <span>{{ __("Language") }}:</span>
                                <form id="languageChangeForm" method="POST" action="{{ route('saas.changeLanguage') }}">
                                    @csrf
                                    <select name="language" id="language">
                                        <option value="en">{{ __('English') }}</option>
                                        <option value="bn">{{ __('Bangla') }}</option>
                                        <option value="ar">{{ __('Arabic') }}</option>
                                    </select>
                                </form>
                            </div> --}}

                            {{-- Message Section --}}
                            {{-- <div class="header-btn-box">
                                <button class="header-btn" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-light fa-comment-dots"></i>
                                    <span class="badge bg-danger">3</span>
                                </button>
                                <ul class="message-dropdown dropdown-menu" aria-labelledby="messageDropdown">
                                    <li>
                                        <a href="#" class="d-flex">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/saas/images/avatar.png') }}" alt="image">
                                            </div>
                                            <div class="msg-txt">
                                                <span class="name">Archer Cowie</span>
                                                <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                <span class="time">2 Hours ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/saas/images/avatar-2.png') }}" alt="image">
                                            </div>
                                            <div class="msg-txt">
                                                <span class="name">Cody Rodway</span>
                                                <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                <span class="time">2 Hours ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/saas/images/avatar-3.png') }}" alt="image">
                                            </div>
                                            <div class="msg-txt">
                                                <span class="name">Zane Bain</span>
                                                <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                <span class="time">2 Hours ago</span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="show-all-btn">Show all message</a>
                                    </li>
                                </ul>
                            </div> --}}

                            {{-- Notification --}}
                            <div class="header-btn-box">
                                <button class="header-btn" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-light fa-bell"></i>
                                    <span class="badge bg-danger">0</span>
                                </button>
                                <ul class="notification-dropdown dropdown-menu" aria-labelledby="notificationDropdown">
                                    {{-- <li>
                                        <a href="#" class="d-flex align-items-center">
                                            <div class="avatar">
                                                <img src="{{ asset('modules/saas/images/avatar.png') }}" alt="image">
                                            </div>
                                            <div class="notification-txt">
                                                <span class="notification-icon text-primary"><i class="fa-solid fa-thumbs-up"></i></span> <span class="fw-bold">Archer</span> Likes your post
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="show-all-btn">Show all message</a>
                                    </li> --}}
                                    <li>
                                        <a href="#" class="d-flex align-items-center">
                                            <div class="notification-txt">
                                                {{ __("Empty notifications") }}
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <button class="header-btn fullscreen-btn" id="btnFullscreen"><i class="fa-light fa-expand"></i></button>
                            <button class="header-btn theme-color-btn"><i class="fa-light fa-sun-bright"></i></button>
                        </div>
                    </div>
                </div>
                <button class="header-btn header-collapse-group-btn d-lg-none"><i class="fa-light fa-ellipsis-vertical"></i></button>
                {{-- <button class="header-btn theme-settings-btn"><i class="fa-light fa-gear"></i></button> --}}

                <div class="header-btn-box">
                    <button class="profile-btn" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset(auth()?->user()?->photo ?? 'modules/saas/images/admin.png') }}" alt="image">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li>
                            <div class="dropdown-txt text-center">
                                <p class="mb-0">{{ auth()->user()?->name }}</p>
                                <span class="d-block">{{ auth()->user()?->roles?->first()->name }}</span>
                            </div>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('saas.profile.edit', auth()->user()->id ) }}"><span class="dropdown-icon"><i class="fa-regular fa-circle-user"></i></span>{{ __("Profile") }}</a></li>
                        {{-- <li><a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Message</a></li>
                        <li><a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-calendar-check"></i></span> Taskboard</a></li> --}}
                        <li><a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-circle-question"></i></span> {{ __("Help") }}</a></li>
                        <li><a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-gear"></i></span> {{ __("Settings") }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            {{-- <a class="dropdown-item" href="#" role="button" onclick="event.preventDefault();alert('hi')"> --}}
                            <a class="dropdown-item" href="#" role="button" onclick="event.preventDefault();document.getElementById('logoutForm').submit()">
                                <span class="dropdown-icon">
                                    <i class="fa-regular fa-arrow-right-from-bracket"></i>
                                </span> {{ __("Logout") }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- header end -->
<form method="POST" action="{{ route('saas.logout') }}" id="logoutForm">
    @csrf
</form>
