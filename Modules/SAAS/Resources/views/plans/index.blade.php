<x-saas::admin-layout title="Manage Plan">
    @push('css')
        <style>

        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Manage Plans') }}</h5>
            <div>
                <a href="{{ route('saas.plans.create') }}" class="btn btn-primary">{{ __('Create Plan') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('SL No.') }}</th>
                                    <th>{{ __('Plan Name') }}</th>
                                    <th>{{ __('Period Value') }}</th>
                                    <th>{{ __('Period Unit') }}</th>
                                    <th>{{ __('Currency Code') }}</th>
                                    <th>{{ __('Period Price') }}</th>
                                    <th>{{ __('Plan Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $key => $plan)
                                    <tr class="">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $plan->period_value }}</td>
                                        <td>{{ $plan->period_unit }}</td>
                                        <td>{{ $plan->currency_code }}</td>
                                        <td>{{ $plan->price }}</td>
                                        <td>{!! $plan->statusLabel !!}</td>
                                        <td class="">
                                            <a href="{{ route('saas.plans.edit', $plan->id) }}"
                                                class="btn btn-primary text-white">
                                                {{ __('Edit') }}
                                            </a>
                                            <a href="{{ route('saas.plans.destroy', $plan->id) }}"
                                                class="btn btn-danger text-white delete-button delete-btn1">
                                                {{ __('Delete') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $plans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('.delete-button').click(function(e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    if (window.confirm('Delete permanently?')) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(data) {
                                toastr.success(data);
                                window.location.reload();
                            },
                            error: function(data) {
                                toastr.error(data);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
</x-saas::admin-layout>
