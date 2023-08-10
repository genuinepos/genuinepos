<x-saas::admin-layout title="Create tenant">
    @push('css')
    <style>
        tr, th, td {
            text-align: start !important;
            border: 1px solid black;
        }
    </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Manage Business') }}</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SL No.</th>
                                    <th>Business Name</th>
                                    <th>Domain</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                    @php
                                        $domain = $tenant?->domains()?->first()?->domain;
                                        $domain = str_contains($domain, '.') ? $domain : $domain . '.' . config('app.domain');
                                        $domain = 'http://' . $domain;
                                    @endphp
                                    <tr class="text-start">
                                        <td>{{ $tenant->id }}</td>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $domain }}</td>
                                        <td class="text-start">
                                            <span class="badge bg-primary px-2">
                                                <a href="{{ $domain }}" target="_blank">
                                                    {{ __('Open App') }}
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-saas::admin-layout>
