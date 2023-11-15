<x-saas::admin-layout title="Create tenant">
    @push('css')
    <style>

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
                                    <th>{{ __("SL No.") }}</th>
                                    <th>{{ __("Business Name") }}</th>
                                    <th>{{ __("Domain") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                    @php
                                        $domain = $tenant?->domains()?->first()?->domain;
                                        $domain = str_contains($domain, '.') ? $domain : $domain . '.' . config('app.domain');
                                        $domain = 'http://' . $domain;
                                    @endphp
                                    <tr class="">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $domain }}</td>
                                        <td class="">
                                            <a href="{{ $domain }}" target="_blank" role="button" class="btn btn-primary btn-sm text-white">
                                                {{ __('Open Business') }}
                                            </a>
                                            {{-- <a href="{{ $domain }}" target="_blank" role="button" class="btn btn-danger btn-sm text-white">
                                                {{ __('Delete Business') }}
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                
                            </tfoot>
                        </table>
                        <div class="pt-1">
                            {{ $tenants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-saas::admin-layout>
