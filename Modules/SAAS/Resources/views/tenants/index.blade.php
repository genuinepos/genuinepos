<x-saas::admin-layout title="All Customer">
    @push('css')
        <style>
            .main-content .digi-dataTable thead th {
                font-weight: 400;
                padding: 4px 22px 5px 4px;
                background-position-x: calc(100% - 10px);
                background-size: 9px;
                font-size: 11px;
            }

            .main-content .digi-dataTable tr td {
                vertical-align: middle;
                padding: 6px 5px 4px 3px;
                font-size: 11px;
            }

            table.dataTable tfoot th, table.dataTable tfoot td {
                padding: 4px 17px 4px 4px;
                border-top: 1px solid #111;
                font-size: 11px;
            }

            table td .btn-sm {
                padding: 1px 6px!important;
                font-size: 11px!important;
            }

            .dropdown-menu {
                padding: 2px 0;
            }

            .dropdown-menu .dropdown-item {
                font-size: 11px;
                padding: 2px 7px;
            }
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
                        <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="tenantsTable">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('Action') }}</th>
                                    <th class="text-start">{{ __('Customer Name') }}</th>
                                    <th class="text-start">{{ __('Email') }}</th>
                                    <th class="text-start">{{ __('Phone') }}</th>
                                    <th class="text-start">{{ __('Business Name') }}</th>
                                    <th class="text-start">{{ __('Store Url') }}</th>
                                    <th class="text-start text-dark">{{ __('Plan') }}</th>
                                    <th class="text-start"> {{ __('Store Count') }}</th>
                                    <th class="text-start">{{ __('Has Company') }}</th>
                                    <th class="text-start">{{ __('Payment Status') }}</th>
                                    <th class="text-start">{{ __('Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            var tenantsTable = $(".digi-dataTable").DataTable({
                processing: true,
                serverSide: true,
                searchable: true,
                "pageLength": 100,
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('saas.tenants.index') }}",
                columns: [
                    {
                        data: 'action',
                        className: 'text-start'
                    },
                    {
                        data: 'user_name',
                        name: 'users.name',
                        className: 'text-start'
                    },
                    {
                        data: 'email',
                        name: 'users.email',
                        className: 'text-start'
                    },
                    {
                        data: 'phone',
                        name: 'users.phone',
                        className: 'text-start'
                    },

                    {
                        data: 'business_name',
                        name: 'tenants.data',
                        className: 'text-start'
                    },
                    {
                        data: 'domain',
                        name: 'domains.domain',
                        className: 'text-start'
                    },
                    {
                        data: 'plan',
                        name: 'plans.name',
                        className: 'text-start text-success'
                    },
                    {
                        data: 'current_shop_count',
                        name: 'user_subscriptions.current_shop_count',
                        className: 'text-start fw-bold'
                    },

                    {
                        data: 'has_business',
                        name: 'user_subscriptions.current_shop_count',
                        className: 'text-start fw-bold'
                    },
                    {
                        data: 'payment_status',
                        name: 'user_subscriptions.current_shop_count',
                        className: 'text-start fw-bold'
                    },
                    {
                        data: 'created_at',
                        name: 'tenants.created_at',
                        className: 'text-start'
                    }
                ],
            });
        </script>
    @endpush
</x-saas::admin-layout>
