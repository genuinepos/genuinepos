<x-saas::admin-layout title="Manage Plan">
    @push('css')
    <style>

    </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Manage Plan') }}</h5>
            <div>
                <a href="{{ route('saas.plans.create') }}" class="btn btn-primary">{{ __('Create Plan') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __("SL No.") }}</th>
                                    <th>{{ __("Plan Name") }}</th>
                                    <th>{{ __("Price") }}</th>
                                    <th>{{ __("Period Month") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $key => $plan)
                                    <tr class="">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $plan->name }}</td>
                                        <td>{{ $plan->price }}</td>
                                        <td>{{ $plan->periodType }}</td>
                                        <td class="">
                                            <a href="{{route('saas.plans.edit', $plan->id)}}" class="">
                                                {{ __('Edit Plan') }}
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
</x-saas::admin-layout>
