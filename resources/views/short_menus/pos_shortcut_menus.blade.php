@foreach ($shortMenuUsers as $shortMenuUser)
    @if (isset($shortMenuUser?->shortMenu))
        @php
            $split = explode(',', $shortMenuUser?->shortMenu?->url);
            $param = isset($split[1]) ? $split[1] : null;
            $__route = isset($split[0]) ? $split[0] : $shortMenuUser?->shortMenu?->url;
        @endphp
        <li>
            <a href="{{ route($__route, $param) }}" title="{{ $shortMenuUser?->shortMenu?->name }}" class="head-tbl-icon" tabindex="-1"><span class="{{ $shortMenuUser?->shortMenu?->icon }}"></span></a>
        </li>
    @endif
@endforeach

<li>
    <a href="{{ route('short.menus.modal.form', \App\Enums\ShortMenuScreenType::PosScreen->value) }}" id="addPosShortcutBtn" class="head-tbl-icon border-none" tabindex="-1"><span class="fas fa-plus"></span></a>
</li>
