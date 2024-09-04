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
                            <th class="text-start" style="font-size: 12px;">{{ __('Business') }}</th>
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
                            <th class="text-start" style="font-size: 12px;">{{ __('Store Url') }}</th>
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
                        <div class="col-md-4" style="border-right:1px solid!important">
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="start_date">{{ __('Net Total Amount') }}</label>
                                    <input name="net_total" type="number" id="net_total" class="form-control form-control-sm fw-bold" value="{{ $dueTransaction->net_total }}">
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="discount">{{ __('Discount Amount') }} (<span id="show_discount_percent">{{ $dueTransaction->discount_percent }}%</span>)</label>
                                    <div class="input-group">
                                        <input readonly name="discount_percent" type="hidden" id="discount_percent" class="form-control form-control-sm w-50 d-inline" value="{{ $dueTransaction->discount_percent }}">
                                        <input name="discount" type="number" id="discount" class="form-control form-control-sm fw-bold" value="{{ $dueTransaction->discount }}" placeholder="{{ __('0.00') }}" autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="start_date">{{ __('Total Payable Amount') }}</label>
                                    <input readonly name="total_payable_amount" type="number" class="form-control form-control-sm fw-bold" id="total_payable_amount" value="{{ $dueTransaction->total_payable_amount }}" autocomplete="off">
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="start_date">{{ __('Due Amount') }}</label>
                                    <input readonly name="due" type="number" class="form-control form-control-sm fw-bold text-danger" id="due" value="{{ $dueTransaction->due }}" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12 col-sm-12">
                                    <label for="payment_status">{{ __('Payment Status') }}</label>
                                    <select required name="payment_status" id="payment_status" class="form-control form-control-sm">
                                        <option value="0">{{ __('Pending') }}</option>
                                        <option value="1">{{ __('Paid') }}</option>
                                    </select>
                                </div>

                                <div class="col-xxl-12 col-lg-12 col-sm-12 repayment_field">
                                    <label for="repayment_date">{{ __('Repayment / Expired Date') }}</label>
                                    <input name="repayment_date" id="repayment_date" class="form-control form-control-sm" value="{{ $tenant?->user?->userSubscription?->due_repayment_date }}" autocomplete="off">
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
                                    <input name="payment_trans_id" class="form-control form-control-sm" id="payment_trans_id" placeholder="{{ __('Payment Transaction ID') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-8 d-flex justify-content-end">
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

                            toastr.error("{{ __('Net Connection Error.') }}");
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

            $(document).on('input', '#discount', function() {

                calculateDiscount();
            });

            function calculateDiscount() {

                var netTotal = $('#net_total').val() ? $('#net_total').val() : 0;
                var discount = $('#discount').val() ? $('#discount').val() : 0;

                var totalPayableAmount = parseFloat(netTotal) - parseFloat(discount);

                $('#total_payable_amount').val(parseFloat(totalPayableAmount).toFixed(2));

                $('#due').val(parseFloat(totalPayableAmount).toFixed(2));

                var discountPercent = (parseFloat(discount) / parseFloat(netTotal)) * 100;

                var __discountPercent = discountPercent ? parseFloat(discountPercent) : 0;

                $('#discount_percent').val(parseFloat(__discountPercent).toFixed(2));
                $('#show_discount_percent').html(parseFloat(__discountPercent).toFixed(2)+'%');
            }
        </script>
    @endpush
</x-saas::admin-layout>
