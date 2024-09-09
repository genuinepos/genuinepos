<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="payment_heading">{{ __('Add Shortcut Menus') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="modal-body_shortcuts">
            <form id="add_shortcut_menu_form" action="{{ route('short.menus.store', $screenType) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="menu-list-area">
                        <ul class="list-unstyled">
                            @foreach ($shortMenus as $shortMenu)
                                @php
                                    $userMenu = $screenType == \App\Enums\ShortMenuScreenType::DashboardScreen->value ? $shortMenu?->userMenuForDashboard : $shortMenu?->userMenuForPos;

                                    $isModuleEnabled = isset($shortMenu->enable_module) && isset($generalSettings["$shortMenu->enable_module"]) ? ($generalSettings["$shortMenu->enable_module"] == 1 ? true : false) : true;
                                @endphp

                                @if ($isModuleEnabled)
                                    @can($shortMenu->permission)
                                        <li>
                                            <p><input name="menu_ids[]" @checked(isset($userMenu)) type="checkbox" value="{{ $shortMenu->id }}" id="check_menu">
                                                <i class="{{ $shortMenu->icon }} text-primary s-menu-icon ms-1"></i> <span class="s-menu-text">{{ $shortMenu->name }}</span>
                                            </p>
                                        </li>
                                    @endcan
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
