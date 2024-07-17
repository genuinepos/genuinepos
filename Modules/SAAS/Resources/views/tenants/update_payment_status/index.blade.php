<x-saas::admin-layout title="Update Payment Status">
    @push('css')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Update Payment Status') }}</h5>
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

            <hr>

            <div class="row">
                <form id="update_payment_status_form" action="{{ route('saas.tenants.update.payment.status.update', $tenant->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="start_date">{{ __('Due Amount') }}</label>
                                    <input readonly type="text" value="{{ \App\Utils\Converter::format_in_bdt($dueTransaction->due) }}" autocomplete="off" class="form-control form-control-sm fw-bold">
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="payment_status">{{ __('Payment Status') }}</label>
                                    <select required name="payment_status" id="payment_status" class="form-control form-control-sm">
                                        <option value="0">{{ __('Pending') }}</option>
                                        <option value="1">{{ __('Paid') }}</option>
                                    </select>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12 repayment_field">
                                    <label for="repayment_date">{{ __('Repayment') }}</label>
                                    <input name="repayment_date" id="repayment_date" class="form-control form-control-sm" value="{{ $tenant?->user?->userSubscription?->due_repayment_date }}">
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12 payment_details_field d-none">
                                    <label for="payment_method_name">{{ __('Payment Method') }}</label>
                                    <select name="payment_method_name" id="payment_method_name" class="form-control form-control-sm">
                                        <option value="N/A">{{ __('Select Payment Method') }}</option>
                                        <option value="Cash">{{ __('Cash') }}</option>
                                        <option value="Card">{{ __('Card') }}</option>
                                        <option value="Bkash">{{ __('Bkash') }}</option>
                                        <option value="Recket">{{ __('Recket') }}</option>
                                        <option value="Naged">{{ __('Naged') }}</option>
                                    </select>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12 payment_details_field d-none">
                                    <label for="payment_method_name">{{ __('Payment Transaction ID') }}</label>
                                    <input name="payment_trans_id" id="payment_trans_id" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn btn-sm btn-success update_payment_status_loading_btn" style="display: none;">{{ __('Loading') }}...</span></button>
                                <button type="submit" id="update_payment_status_date_save" class="btn btn-sm btn-success update_payment_status_submit_button">{{ __('Update') }}</button>
                            </div>
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
                $("#repayment_date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });

            $(document).on('submit', '#update_payment_status_form', function(e) {

                e.preventDefault();

                var url = $(this).attr('action');
                var request = $(this).serialize();
                $('.update_payment_status_loading_btn').show();
                $('.update_payment_status_submit_button').hide();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: request,
                    success: function(data) {

                        $('.update_payment_status_loading_btn').hide();
                        $('.update_payment_status_submit_button').show();

                        if (!$.isEmptyObject(data.errorMsg)) {

                            toastr.error(data.errorMsg);
                            return;
                        }

                        toastr.success(data);
                        window.location = "{{ url()->previous() }}";
                    },
                    error: function(err) {

                        $('.update_payment_status_loading_btn').hide();
                        $('.update_payment_status_submit_button').show();
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

            $(document).on('change', '#payment_status', function() {

                $('.repayment_field').addClass('d-none');
                $('#repayment_date').prop('required', false);
                $('.payment_details_field').addClass('d-none');

                var paymentStatus = $(this).val();

                if (paymentStatus == 1) {

                    $('.repayment_field').addClass('d-none');
                    $('#repayment_date').prop('required', false);
                    $('.payment_details_field').removeClass('d-none');
                } else if (paymentStatus == 0) {

                    $('.repayment_field').removeClass('d-none');
                    $('#repayment_date').prop('required', true);
                    $('.payment_details_field').addClass('d-none');
                }

            });
        </script>
    @endpush
</x-saas::admin-layout>
