<x-saas::admin-layout title="coupons">
    @push('css')
        <style>

        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Coupons') }}</h5>
            <div>
                <a href="{{route('saas.coupons.create')}}" class="btn btn-sm btn-primary">{{ __('Create Coupon') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col table-responsive">
                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="userTable">
                        <thead>
                            <tr>
                                <th>{{ __('SL No.') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Percent') }}</th>
                                <th>{{ __('No Of Usage') }}</th>
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
            var table = $("#userTable").DataTable({
                ajax: {
                    url: "{{ route('saas.coupons.index') }}",
                    type: 'GET'
                },
                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex'
                    },
                    {
                        name: 'code',
                        data: 'code'
                    },
                    {
                        name: 'start_date',
                        data: 'start_date'
                    },
                    {
                        name: 'end_date',
                        data: 'end_date'
                    },
                    {
                        name: 'percent',
                        data: 'percent'
                    },
                    {
                        name: 'no_of_usage',
                        data: 'no_of_usage'
                    },
                    {
                        name: 'action',
                        data: 'action'
                    }
                ],
                order: [[1, 'desc']]
            });
        </script>
    @endpush
</x-saas::admin-layout>
