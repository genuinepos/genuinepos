<x-saas::admin-layout title="Manage Plan">
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

            table.dataTable tfoot th,
            table.dataTable tfoot td {
                padding: 4px 17px 4px 4px;
                border-top: 1px solid #111;
                font-size: 11px;
            }

            table td .btn-sm {
                padding: 1px 6px !important;
                font-size: 11px !important;
            }
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
                        <table class="table table-dashed table-hover digi-dataTable table-striped">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __('SL No.') }}</th>
                                    <th class="text-start">{{ __('Plan Name') }}</th>
                                    <th class="text-start">{{ __('Price Per Month') }}</th>
                                    <th class="text-start">{{ __('Price Per Year') }}</th>
                                    <th class="text-start">{{ __('Lifetime Price') }}</th>
                                    <th class="text-start">{{ __('Applicable Lifetime Years') }}</th>
                                    <th class="text-start">{{ __('Plan Status') }}</th>
                                    <th class="text-start">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $key => $plan)
                                    <tr class="">
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td class="text-start {{ $plan->is_trial_plan == 1 ? 'text-danger' : '' }}">
                                            @if ($plan->is_trial_plan == 1)
                                                {!! $plan->name . ' <samll style="font-size: 10px!important;">(Period : ' . $plan->trial_days . ' Days)</samll>' !!}
                                            @else
                                                {!! $plan->name . ' <samll style="font-size: 10px!important;"> | ' . \App\Enums\PlanType::tryFrom($plan->plan_type)->name . ' </samll>' !!}
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($plan->is_trial_plan == 1)
                                                {{ __('N/A') }}
                                            @else
                                                <span class="fw-bold">
                                                    {{ \App\Utils\Converter::format_in_bdt($plan->price_per_month) }}
                                                </span>
                                                <p>{{ __('Back Office') }}:
                                                    <span class="fw-bold">
                                                        {{ \App\Utils\Converter::format_in_bdt($plan->business_price_per_month) }}
                                                    </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($plan->is_trial_plan == 1)
                                                {{ __('N/A') }}
                                            @else
                                                <span class="fw-bold">
                                                    {{ \App\Utils\Converter::format_in_bdt($plan->price_per_year) }}
                                                </span>
                                                <p>{{ __('Back Office') }}:
                                                    <span class="fw-bold">
                                                        {{ \App\Utils\Converter::format_in_bdt($plan->business_price_per_year) }}
                                                    </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($plan->is_trial_plan == 1)
                                                {{ __('N/A') }}
                                            @else
                                                <span class="fw-bold">
                                                    {{ \App\Utils\Converter::format_in_bdt($plan->lifetime_price) }}
                                                </span>
                                                <p>{{ __('Back Office') }}:
                                                    <span class="fw-bold">
                                                        {{ \App\Utils\Converter::format_in_bdt($plan->business_lifetime_price) }}
                                                    </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            @if ($plan->is_trial_plan == 1)
                                                {{ __('N/A') }}
                                            @else
                                                {{ $plan->applicable_lifetime_years }}
                                            @endif
                                        </td>
                                        <td class="text-start">{!! $plan->statusLabel !!}</td>
                                        <td class="text-start">
                                            <a href="{{ route('saas.plans.edit', $plan->id) }}" class="btn btn-sm btn-primary text-white">
                                                {{ __('Edit') }}
                                            </a>
                                            <a href="{{ route('saas.plans.destroy', $plan->id) }}" class="btn btn-sm btn-danger text-white delete-button delete-btn1">
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

                                if (!$.isEmptyObject(data.errorMsg)) {

                                    toastr.error(data.errorMsg);
                                    return;
                                }

                                toastr.success(data);
                                // window.location.reload();
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
