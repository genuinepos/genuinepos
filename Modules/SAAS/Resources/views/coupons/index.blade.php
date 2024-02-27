<x-saas::admin-layout title="coupons">
    @push('css')
    <style>
    .modal-backdrop {
        --bs-backdrop-zindex: 0;
        --bs-backdrop-bg: #000;
        --bs-backdrop-opacity: 0;
        position: fixed;
        top: 0;
        left: 0;
        z-index: var(--bs-backdrop-zindex);
        width: 100vw;
        height: 100vh;
        background-color: var(--bs-backdrop-bg);
    }
    </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Coupons') }}</h5>
            <div>
                <!-- <a href="{{route('saas.coupons.create')}}" class="btn btn-sm btn-primary">{{ __('Create Coupon') }}</a> -->
                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                    class="btn btn-sm btn-primary">{{ __('Create Coupon') }}</a>
            </div>
        </div>
        <div class="panel-body">


            <!-- Add or Edit Modal -->
            @include('saas::Coupons.Modal.add')


            <div class="row">
                <div class="col table-responsive">
                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped"
                        id="userTable">
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
        order: [
            [1, 'desc']
        ]
    });
    </script>
    @endpush
</x-saas::admin-layout>