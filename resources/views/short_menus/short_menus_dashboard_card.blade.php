@foreach ($shortMenuUsers as $shortMenuUser)
    @php
        $isModuleEnabled = isset($shortMenuUser->shortMenu->enable_module) && isset($generalSettings[$shortMenuUser->shortMenu->enable_module]) ? ($generalSettings[$shortMenuUser->shortMenu->enable_module] == 1 ? true : false) : true;
    @endphp
    @if (isset($shortMenuUser->shortMenu))
        @if ($isModuleEnabled)
            @can($shortMenuUser->shortMenu->permission)
                <div class="switch_bar">
                    @php
                        $split = explode(',', $shortMenuUser?->shortMenu?->url);
                        $param = isset($split[1]) ? $split[1] : null;
                        $__route = isset($split[0]) ? $split[0] : $shortMenuUser?->shortMenu?->url;
                    @endphp
                    <a href="{{ route($__route, $param) }}" class="bar-link">
                        <span><i class="{{ $shortMenuUser?->shortMenu?->icon }}"></i></span>
                    </a>
                    <p>{{ $shortMenuUser?->shortMenu?->name }}</p>
                </div>
            @endcan
        @endif
    @endif
@endforeach
<div class="switch_bar">
    <a href="{{ route('short.menus.modal.form', \App\Enums\ShortMenuScreenType::DashboardScreen->value) }}" class="bar-link" id="addShortcutBtn">
        <span><i class="fas fa-plus-square text-white"></i></span>
    </a>
    <p>{{ __('Add Shortcut') }}</p>
</div>
