<x-admin::admin-layout title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Business List') }}</h2>
            </div>
            <div class="card-body">
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
    </div>
</x-admin::admin-layout>
