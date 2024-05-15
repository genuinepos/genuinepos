@foreach ($shortMenuUsers as $shortMenuUser)
    @if (isset($shortMenuUser?->shortMenu))
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
    @endif
@endforeach
<div class="switch_bar">
    <a href="{{ route('short.menus.modal.form', \App\Enums\ShortMenuScreenType::DashboardScreen->value) }}" class="bar-link" id="addShortcutBtn">
        <span><i class="fas fa-plus-square text-white"></i></span>
    </a>
    <p>{{ __('Add Shortcut') }}</p>
</div>
