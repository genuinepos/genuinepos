    <!-- main sidebar start -->
    <div class="main-sidebar">
        <div class="main-menu">
            <ul class="sidebar-menu scrollable">
                <li class="sidebar-item">
                    <a role="button" class="sidebar-link-group-title has-sub">Dashboard</a>
                    <ul class="sidebar-link-group">
                        <li class="sidebar-dropdown-item">
                            <a role="button" class="sidebar-link has-sub" data-dropdown="hrmDropdown"><span class="nav-icon"><i class="fa-light fa-user-tie"></i></span> <span class="sidebar-txt">Business</span></a>
                            <ul class="sidebar-dropdown-menu" id="hrmDropdown">
                                <li class="sidebar-dropdown-item">
                                    <a href="{{ route('saas.tenants.create') }}" class="sidebar-link">
                                        New Business
                                    </a>
                                </li>
                                <li class="sidebar-dropdown-item"><a href="{{ route('saas.tenants.index') }}" class="sidebar-link">Business List</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-dropdown-item">
                            <a href="#" class="sidebar-link"><span class="nav-icon"><i class="fa-light fa-user-plus"></i></span> <span class="sidebar-txt">Contacts</span></a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="help-center">
                    <h3>Help Center</h3>
                    <p>We're an award-winning, forward thinking</p>
                    <a href="#" class="btn btn-sm btn-light">Go to Help Center</a>
                </li> --}}
            </ul>
        </div>
    </div>
    <!-- main sidebar end -->
