<x-admin::admin-layout title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Successfully shop created!') }}</h2>
            </div>
            <div class="card-body">
                @php
                $domain = $tenant?->domains()?->first()?->domain;
                $domain = (str_contains($domain,'.')) ? $domain : $domain . '.pos.test';
                $domain = 'http://' . $domain;
                @endphp
                <span class="me-4"><b>{{ $domain }}</b></span> <a href="{{ $domain }}" target="_blank">Access the App</a>
            </div>
        </div>
    </div>
</x-admin::admin-layout>
