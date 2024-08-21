<x-saas::admin-layout title="Users">
    @push('css')
        <style>

        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Users') }}</h5>
            <div>
                @if (auth()->user()->can('users_create'))

                    <a href="{{ route('saas.users.create') }}" class="btn btn-sm btn-primary">{{ __('Add User') }}</a>
                @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col table-responsive">
                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="userTable">
                        <thead>
                            <tr>
                                <th>{{ __('SL No.') }}</th>
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('User Type') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            var table = $("#userTable").DataTable({
                ajax: {
                    url: "{{ route('saas.users.index') }}",
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
                        name: 'email',
                        data: 'email'
                    },
                    {
                        name: 'phone',
                        data: 'phone'
                    },
                    {
                        name: 'role',
                        data: 'role'
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
