<nav class="navbar navbar-expand-lg @auth navbar-dark bg-secondary @else navbar-light bg-light @endauth">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    @auth
                    <a class="nav-link active" aria-current="page" href="{{ route('saas.dashboard') }}">Dashboard</a>
                    @else
                    <a class="nav-link active" aria-current="page" href="{{ route('saas.welcome-page') }}">Home</a>
                    @endauth
                </li>
                @auth

                <li class="nav-item dropdown  float-end">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownShop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Shops
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownShop">
                        <li><a class="dropdown-item" href="{{ route('saas.tenants.index') }}">All Shops</a></li>
                        <li><a class="dropdown-item" href="{{ route('saas.tenants.create') }}">Create New</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown  float-end">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownSetting" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profile
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownSetting">
                        <li><a class="dropdown-item" href="{{ route('saas.profile.edit', auth()->user()->id) }}">Edit Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li  onclick="handleLogout()"><a class="dropdown-item" href="#" >Logout</a></li>
                    </ul>
                </li>
                @endauth
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
<form class="d-none" id="logout-form" method="POST" action="{{ route('saas.logout') }}">
    @csrf
    <input type="submit" value="">
</form>
