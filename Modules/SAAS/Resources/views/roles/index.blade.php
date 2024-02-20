<x-saas::admin-layout title="Roles">
    @push('css')
        <style>

        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Roles') }}</h5>
            <div>
                <a href="{{ route('saas.roles.create') }}" class="btn btn-sm btn-primary">{{ __('Create Role') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col table-responsive">
                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="roleTable">
                        <thead>
                            <tr>
                                <th>{{ __('SL No.') }}</th>
                                <th>{{ __('Role Name') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            var table = $("#roleTable").DataTable({
                ajax: {
                    url: "{{ route('saas.roles.index') }}",
                    type: 'GET'
                },
                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex'
                    },
                    {
                        name: 'name',
                        data: 'name'
                    },
                    {
                        name: 'action',
                        data: 'action'
                    }
                ]
            });
        </script>
    @endpush
</x-saas::admin-layout>
