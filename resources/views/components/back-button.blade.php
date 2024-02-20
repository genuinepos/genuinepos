@props(['href' => '#', 'text' => 'Go Back'])
<a href="{{ $href }}" class="btn btn-sm btn-outline-primary">
    <i class="fa-solid fa-arrow-left"></i>
    {{ __($text) }}
</a>
