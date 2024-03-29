<x-saas::admin-layout title="All Customer">
    @push('css')
    <style>

    </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Manage Customers') }}</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __("SL No.") }}</th>
                                    <th>{{ __("Business Name") }}</th>
                                    <th>{{ __("Domain") }}</th>
                                    <th>{{ __("Plan") }}</th>
                                    <th>{{ __("Created At") }}</th>
                                    <th>{{ __("Expire At") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tenants as $tenant)
                                    @php
                                        $domain = $tenant?->domains()?->first()?->domain;
                                        if(isset($domain)) {
                                            $domain = \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($domain);
                                        }
                                    @endphp
                                    <tr class="">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tenant->name }}</td>
                                        <td>{{ $domain }}</td>
                                        <td>{{ $tenant?->plan?->name }}</td>
                                        <td>{{ $tenant->created_at }}</td>
                                        <td>{{ $tenant?->expire_date }}</td>
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
