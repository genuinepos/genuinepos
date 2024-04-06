<!-- main sidebar start -->
<div class="main-sidebar">
    <div class="main-menu">
        <ul class="sidebar-menu scrollable">
            <li class="sidebar-item">
                <a role="button" class="sidebar-link-group-title has-sub">{{ __('Dashboard') }}</a>
                <ul class="sidebar-link-group">
                    @canany(['tenants_index', 'tenants_create'])
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="customerDropdown">
                                <span class="nav-icon"><i class="fa-light fa-user-tie"></i></span>
                                <span class="sidebar-txt">{{ __('Subscriptions') }}</span>
                            </a>
                            <ul class="sidebar-dropdown-menu" id="customerDropdown">
                                @can('tenants_create')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.tenants.create') }}" class="sidebar-link">
                                            {{ __('Add Customer') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('tenants_index')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.tenants.index') }}" class="sidebar-link">{{ __('All Customer') }}</a>
                                    </li>
                                @endcan
                                <li class="sidebar-dropdown-item">
                                    <a href="#" class="sidebar-link">{{ __('Payment Histories') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endcanany
                </ul>

                <ul class="sidebar-link-group">
                    @canany(['transactions'])
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="billingDropdown">
                                <span class="nav-icon"><i class="fa-light fa-chart-simple"></i></span>
                                <span class="sidebar-txt">{{ __('Billing') }}</span>
                            </a>
                            <ul class="sidebar-dropdown-menu" id="billingDropdown">
                                @can('transactions')
                                    <li class="sidebar-dropdown-item">
                                        <a href="#" class="sidebar-link">
                                            {{ __('Transaction') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                </ul>

                @canany(['plans_index'])
                    <ul class="sidebar-link-group">
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="planManagementDropdown">
                                <span class="nav-icon"><i class="fa-light fa-chart-simple"></i></span>
                                <span class="sidebar-txt">{{ __('Manage Plans') }}</span>
                            </a>
                            <ul class="sidebar-dropdown-menu" id="planManagementDropdown">
                                @can('plans_index')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.plans.index') }}" class="sidebar-link">{{ __('Plans') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                @endcanany

                @canany(['users_index', 'roles_index'])
                    <ul class="sidebar-link-group">
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="userManagementDropdown">
                                <span class="nav-icon"><i class="fa-regular fa-user"></i></span>
                                <span class="sidebar-txt">{{ __('User Management') }}</span>
                            </a>
                            <ul class="sidebar-dropdown-menu" id="userManagementDropdown">
                                @can('users_index')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.users.index') }}" class="sidebar-link">{{ __('Users') }}</a>
                                    </li>
                                @endcan

                                @can('roles_index')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.roles.index') }}" class="sidebar-link">{{ __('Roles') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                @endcanany

                @canany(['coupons_index'])
                    <ul class="sidebar-link-group">
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="cuponsDropdown">
                                <span class="nav-icon"><i class="fa-solid fa-check"></i></span>
                                <span class="sidebar-txt">{{ __('Coupons') }}</span>
                            </a>
                            <ul class="sidebar-dropdown-menu" id="cuponsDropdown">
                                @can('coupons_create')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.coupons.create') }}" class="sidebar-link">{{ __('Add Cupon') }}</a>
                                    </li>
                                @endcan

                                @can('coupons_index')
                                    <li class="sidebar-dropdown-item">
                                        <a href="{{ route('saas.coupons.index') }}" class="sidebar-link">{{ __('All Cupons') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                @endcanany

                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="paymentDropdown">
                            <span class="nav-icon"><i class="fa-regular fa-credit-card"></i></span>
                            <span class="sidebar-txt">{{ __('Payments') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="paymentDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Payment Methods') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="sasSettingsDropdown">
                            <span class="nav-icon"><i class="fa-brands fa-servicestack"></i></span>
                            <span class="sidebar-txt">{{ __('SAAS Settings') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="sasSettingsDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Currencies Methods') }}</a>
                            </li>

                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('General Settings') }}</a>
                            </li>

                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Notification Settings') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="supportTicketDropdown">
                            <span class="nav-icon"><i class="fa-solid fa-ticket"></i></span>
                            <span class="sidebar-txt">{{ __('Support Ticket') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="supportTicketDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Create Ticket') }}</a>
                            </li>

                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('All Ticket') }}</a>
                            </li>

                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Categories') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="sidebar-link-group">
                    <li class="sidebar-dropdown-item">
                        <a role="button" class="sidebar-link has-sub" data-dropdown="setupDropdown">
                            <span class="nav-icon"><i class="fa-solid fa-sliders"></i></span>
                            <span class="sidebar-txt">{{ __('Set-up') }}</span>
                        </a>
                        <ul class="sidebar-dropdown-menu" id="setupDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="#" class="sidebar-link">{{ __('Settings') }}</a>
                            </li>
                        </ul>
                        <ul class="sidebar-dropdown-menu" id="setupDropdown">
                            <li class="sidebar-dropdown-item">
                                <a href="{{route('saas.email-settings.index')}}" class="sidebar-link">{{ __('Email') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            @role('customer')
            <li class="help-center">
                <h3>Help Center</h3>
                <p>We're an award-winning, forward thinking</p>
                <a href="#" class="btn btn-sm btn-light">Go to Help Center</a>
            </li>
            @endrole
        </ul>
    </div>
</div>
<!-- main sidebar end -->
