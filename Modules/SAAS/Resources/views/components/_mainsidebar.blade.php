<!-- main sidebar start -->
<div class="main-sidebar">
    <div class="main-menu">
        <ul class="sidebar-menu scrollable">
            <li class="sidebar-item">
                <a role="button" class="sidebar-link-group-title has-sub">{{ __('Dashboard') }}</a>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="hrmDropdown">
                            <span class="nav-icon"><i class="fa-light fa-user-tie"></i></span>
                            <span class="sidebar-txt">{{ __('Business') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="hrmDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('saas.tenants.create') }}" class="sidebar-link">
                                    {{ __('Create new') }}
                                </a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('saas.tenants.index') }}" class="sidebar-link">{{ __('Business List') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="billingDropdown">
                            <span class="nav-icon"><i class="fa-light fa-chart-simple"></i></span>
                            <span class="sidebar-txt">{{ __('Billing') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="billingDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">
                                    {{ __('Transaction') }}
                                </a>
                            </li>
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Manage Plan') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="planManagementDropdown">
                            <span class="nav-icon"><i class="fa-light fa-chart-simple"></i></span>
                            <span class="sidebar-txt">{{ __('Manage Plans') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="planManagementDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('saas.plans.index') }}" class="sidebar-link">{{ __('Plans') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="userManagementDropdown">
                            <span class="nav-icon"><i class="fa-light fa-chart-simple"></i></span>
                            <span class="sidebar-txt">{{ __('Manage users') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="userManagementDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="{{ route('saas.users.index') }}" class="sidebar-link">{{ __('Users') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            {{--
            <li class="help-center">
                <h3>Help Center</h3>
                <p>We're an award-winning, forward thinking</p>
                <a href="#" class="btn btn-sm btn-light">Go to Help Center</a>
            </li>
            --}}
        </ul>
    </div>
</div>
<!-- main sidebar end -->
