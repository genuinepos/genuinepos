<x-saas::admin title="Create tenant">
    <div class="container">
        <div class="card  mt-3">
            <div class="card-header">
                <h2>{{ __('Shop List') }}</h2>
            </div>
            <div class="card-body">
                @foreach($tenants as $tenant)
                    <li class="group-list-item">
                        @php
                            $domain =  $tenant?->domains()?->first()?->domain;
                            $domain = (str_contains($domain,'.')) ? $domain : $domain . '.pos.test';
                            $domain = 'http://' . $domain;
                        @endphp
                        <span class="me-4"><b>{{ $domain }}</b></span> <a href="{{ $domain }}" target="_blank">Access the App</a>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
</x-saas::admin>
