<x-saas::admin-layout title="Create tenant">
    <div class="panel">
        <div class="panel-header">
            <h2>{{ __('Business List') }}</h2>
        </div>
        <div class="panel-body">
            @foreach($tenants as $tenant)
                <li class="group-list-item">
                    @php
                        $domain =  $tenant?->domains()?->first()?->domain;
                        $domain = (str_contains($domain,'.')) ? $domain : $domain . '.' . config('app.domain');
                        $domain = 'http://' . $domain;
                    @endphp
                    <span class="me-4"><b>{{ $domain }}</b></span> <a href="{{ $domain }}" target="_blank">
                        {{ __("Access the App") }}
                    </a>
                </li>
            @endforeach
        </div>
    </div>
</x-saas::admin-layout>
