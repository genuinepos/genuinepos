<x-saas::admin-layout title="Update Expire Date">
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Update Expire Date') }}</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table>
                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('Customer') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ $tenant?->user?->name }}</td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('Company') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ $tenant?->name }}</td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('Email') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ $tenant?->user?->email }}</td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('Phone') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ $tenant?->user?->phone }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table>
                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('Subdomain') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ $tenant?->id }}</td>
                        </tr>

                        <tr>
                            <th class="text-start" style="font-size: 12px;">{{ __('App Url') }}</th>
                            <td class="text-start" style="font-size: 12px;">: {{ \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($tenant->id) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-3">
                <form id="update_expire_date_form" action="{{ route('saas.tenants.update.expire.date.confirm', $tenant->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="payment_status" value="1">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-dashed table-hover digi-dataTable table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{ __('S/L') }}</th>
                                        <th class="text-start">{{ __('Store/Company') }}</th>
                                        <th class="text-start">{{ __('Expire Date') }}</th>
                                        <th class="text-start">{{ __('New Expire Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $index = 1;
                                    @endphp
                                    @if ($currentSubscription->has_business)
                                        <tr>
                                            <td class="text-start">{{ $index }}</td>
                                            <td class="text-start">{{ __('Multi Store Management System') }}({{ __('Company') }})</td>
                                            <td class="text-start">
                                                @if (date('Y-m-d') > $currentSubscription->business_expire_date)
                                                    <span class="text-danger">{{ $currentSubscription->business_expire_date }}</span>
                                                @else
                                                    <span class="text-success">{{ $currentSubscription->business_expire_date }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="hidden" name="business_current_expire_date" value="{{ $currentSubscription->business_expire_date }}">
                                                <input type="text" name="business_new_expire_date" class="form-control form-control-sm business_new_expire_date" value="{{ $currentSubscription->business_expire_date }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                            </td>
                                        </tr>
                                        @php
                                            $index++;
                                        @endphp
                                    @endif

                                    @foreach ($shopExpireDateHistories as $shopExpireDateHistory)
                                        <tr>
                                            <td class="text-start">{{ $index }}</td>
                                            <td class="text-start">
                                                <input type="hidden" name="shop_expire_date_history_ids[]" value="{{ $shopExpireDateHistory->id }}">
                                                @if ($shopExpireDateHistory?->branch)
                                                    @if ($shopExpireDateHistory?->branch?->parentBranch)
                                                        {{ $shopExpireDateHistory?->branch?->parentBranch->name . '(' . $shopExpireDateHistory?->branch?->area_name . ')-' . $shopExpireDateHistory?->branch?->branch_code }}
                                                    @else
                                                        {{ $shopExpireDateHistory?->branch?->name . '(' . $shopExpireDateHistory?->branch?->area_name . ')-' . $shopExpireDateHistory?->branch?->branch_code }}
                                                    @endif
                                                @else
                                                    <span class="text-danger"> {{ __('Store Not Yet To Be Created') }}</span>
                                                @endif
                                            </td>

                                            <td class="text-start">
                                                @if (date('Y-m-d') > $shopExpireDateHistory->expire_date)
                                                    <span class="text-danger">{{ $shopExpireDateHistory->expire_date }}</span>
                                                @else
                                                    <span class="text-success">{{ $shopExpireDateHistory->expire_date }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="hidden" name="shop_current_expire_dates[]" value="{{ $shopExpireDateHistory->expire_date }}">
                                                <input required type="text" name="shop_new_expire_dates[]" class="form-control form-control-sm shop_new_expire_date" value="{{ $shopExpireDateHistory->expire_date }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                            </td>
                                        </tr>
                                        @php
                                            $index++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12 d-flex justify-content-end mt-3">
                        <div class="btn-loading">
                            <button type="button" class="btn btn-sm btn-success update_expire_date_loading_btn" style="display: none;">{{ __('Loading') }}...</span></button>
                            <button type="submit" id="update_expire_date_save" class="btn btn-sm btn-success update_expire_date_submit_button">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script>
            $(function() {

                $(".business_new_expire_date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });

                $(".shop_new_expire_date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });

            $(document).on('submit', '#update_expire_date_form', function(e) {

                e.preventDefault();

                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.update_expire_date_loading_btn').show();
                $('.update_expire_date_submit_button').hide();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {

                        $('.update_expire_date_loading_btn').hide();
                        $('.update_expire_date_submit_button').show();

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.success(data);
                        window.location = "{{ url()->previous() }}";
                    },
                    error: function(err) {

                        $('.update_expire_date_loading_btn').hide();
                        $('.update_expire_date_submit_button').show();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        } else if (err.status == 403) {

                            toastr.error("{{ __('Access Denied') }}");
                            return;
                        }

                        toastr.error(err.responseJSON.message);
                    }
                });
            });
        </script>
    @endpush
</x-saas::admin-layout>
