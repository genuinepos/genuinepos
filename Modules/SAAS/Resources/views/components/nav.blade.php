<nav class="navbar navbar-expand-lg px-5 navbar-light bg-light">
    <div class="container-fluid">
    <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-flex align-items-center justify-content-between" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{ route('saas.select-plan') }}">{{ __('Plans') }}</a>
            </li>
            {{-- @auth
                <li class="nav-item dropdown  float-end">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBusiness" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('Businesss') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownBusiness">
                        <li><a class="dropdown-item" href="{{ route('saas.tenants.index') }}">{{ __('All Businesss') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('saas.tenants.create') }}">{{ __('Create New') }}</a></li>
                    </ul>
                </li>
            @endauth --}}
        </ul>
        {{-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="{{ __('Search') }}" aria-label="Search">
                    <button class="btn btn-success" type="submit">{{ __('Search') }}</button>
                </form> --}}

        <ul class="navbar-nav  mb-2 mb-lg-0">
            @auth
                <li class="nav-item dropdown  float-end">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSetting" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()?->user()?->name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownSetting">
                        @can('profile_edit')
                            <li>
                                <a class="dropdown-item" href="{{ route('saas.dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                        @endcan
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li onclick="event.preventDefault();handleLogout();"><a class="dropdown-item" href="#">{{ __('Logout') }}</a></li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</nav>
