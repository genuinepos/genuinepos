@foreach ($shortMenus as $sm)
    <div class="switch_bar">
        <a href="{{ route($sm->url) }}" class="bar-link">
            <span><i class="{{ $sm->icon }}"></i></span>
        </a>
        <p>{{ $sm->name}}</p>
    </div>
@endforeach
<div class="switch_bar">
    <a href="{{ route('short.menus.modal.form') }}" class="bar-link" id="addShortcutBtn">
        <span><i class="fas fa-plus-square text-white"></i></span>
    </a>
    <p>Add Shortcut</p>
</div>
