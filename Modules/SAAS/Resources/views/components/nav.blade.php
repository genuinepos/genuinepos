<nav class="navbar navbar-expand-lg px-5 @auth navbar-dark bg-secondary @else navbar-light bg-light @endauth">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex align-items-center justify-content-between"
            id="navbarSupportedContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    @auth
                        <a class="nav-link active" aria-current="page"
                            href="{{ route('saas.dashboard') }}">{{ __('Dashboard') }}</a>
                    @else
                        <a class="nav-link active" aria-current="page"
                            href="{{ route('saas.welcome-page') }}">{{ __('Home') }}</a>
                    @endauth
                </li>
                @auth

                    <li class="nav-item dropdown  float-end">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownShop" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('Shops') }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownShop">
                            {{-- @can('tenants_index') --}}
                            <li><a class="dropdown-item" href="{{ route('saas.tenants.index') }}">{{ __('All Shops') }}</a>
                            </li>
                            {{-- @endcan --}}
                            {{-- @can('tenants_create') --}}
                            <li><a class="dropdown-item"
                                    href="{{ route('saas.tenants.create') }}">{{ __('Create New') }}</a></li>
                            {{-- @endcan --}}
                        </ul>
                    </li>
                @endauth
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="{{ __('Search') }}" aria-label="Search">
                <button class="btn btn-success" type="submit">{{ __('Search') }}</button>
            </form>

            <ul class="navbar-nav  mb-2 mb-lg-0">
                @auth
                    <li class="nav-item dropdown  float-end">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSetting" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()?->user()?->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownSetting">
                            @can('profile_edit')
                                <li><a class="dropdown-item"
                                        href="{{ route('saas.profile.edit', auth()->user()->id) }}">{{ __('Edit Profile') }}</a>
                                </li>
                            @endcan
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li onclick="handleLogout()"><a class="dropdown-item" href="#">{{ __('Logout') }}</a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<form class="d-none" id="logout-form" method="POST" action="{{ route('saas.logout') }}">
    @csrf
    <input type="submit" value="">
</form>
