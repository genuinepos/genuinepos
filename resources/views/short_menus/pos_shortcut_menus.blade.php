@foreach ($shortMenuUsers as $shortMenuUser)
    @php
        $planFeature = isset($shortMenuUser->shortMenu->plan_feature) && isset($generalSettings['subscription']->features[$shortMenuUser->shortMenu->plan_feature]) ? ($generalSettings['subscription']->features[$shortMenuUser->shortMenu->plan_feature] == 1 ? true : false) : true;

        $isModuleEnabled = isset($shortMenuUser->shortMenu->enable_module) && isset($generalSettings[$shortMenuUser->shortMenu->enable_module]) ? ($generalSettings[$shortMenuUser->shortMenu->enable_module] == 1 ? true : false) : true;
    @endphp
    @if (isset($shortMenuUser->shortMenu))
        @if ($planFeature && $isModuleEnabled)
            @can($shortMenuUser->shortMenu->permission)
                @php
                    $split = explode(',', $shortMenuUser?->shortMenu?->url);
                    $param = isset($split[1]) ? $split[1] : null;
                    $__route = isset($split[0]) ? $split[0] : $shortMenuUser?->shortMenu?->url;
                @endphp
                <li>
                    <a href="{{ route($__route, $param) }}" title="{{ $shortMenuUser?->shortMenu?->name }}" class="head-tbl-icon" tabindex="-1"><span class="{{ $shortMenuUser?->shortMenu?->icon }}"></span></a>
                </li>
            @endcan
        @endif
    @endif
@endforeach

<li>
    <a href="{{ route('short.menus.modal.form', \App\Enums\ShortMenuScreenType::PosScreen->value) }}" id="addPosShortcutBtn" class="head-tbl-icon border-none" tabindex="-1"><span class="fas fa-plus"></span></a>
</li>
