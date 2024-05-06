@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        .input-group-text-sale {
            font-size: 7px !important;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        label.col-2,
        label.col-3,
        label.col-4,
        label.col-5,
        label.col-6 {
            text-align: right;
            padding-right: 10px;
        }

        .checkbox_input_wrap {
            text-align: right;
        }
    </style>
@endpush
@section('title', 'Receive Stock From Shop/Business')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Receive Stock From Shop/Business') }}</h6>
                </div>

                <div class="col-6">
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Bank') }}</a>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="receive_from_branch_form" action="{{ route('receive.stock.from.branch.receive', $transferStock->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Send From') }}</b></label>
                                        <div class="col-7">
                                            @php
                                                $sendFrom = '';
                                                if ($transferStock?->senderBranch) {
                                                    if ($transferStock?->senderBranch?->parentBranch) {
                                                        $sendFrom = $transferStock?->senderBranch?->parentBranch?->name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    } else {
                                                        $sendFrom = $transferStock?->senderBranch?->area_name . '(' . $transferStock?->senderBranch?->area_name . ')';
                                                    }
                                                } else {
                                                    $sendFrom = $generalSettings['business_or_shop__business_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $sendFrom }}">
                                        </div>
                                    </div>

                                    @if ($transferStock?->senderWarehouse)
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __('Send At') }}</b></label>
                                            <div class="col-7">
                                                @php
                                                    $sendAt = $transferStock?->senderWarehouse?->warehouse_name . '-(' . $transferStock?->senderWarehouse?->warehouse_code . ')-(WH)';
                                                @endphp

                                                <input readonly type="text" class="form-control fw-bold" value="{{ $sendAt }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Send To') }}</b></label>
                                        <div class="col-7">
                                            @php
                                                $sendTo = '';
                                                if ($transferStock?->receiverBranch) {
                                                    if ($transferStock?->receiverBranch?->parentBranch) {
                                                        $sendTo = $transferStock?->receiverBranch?->parentBranch?->name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                    } else {
                                                        $sendTo = $transferStock?->receiverBranch?->area_name . '(' . $transferStock?->receiverBranch?->area_name . ')';
                                                    }
                                                } else {
                                                    $sendTo = $generalSettings['business_or_shop__business_name'];
                                                }
                                            @endphp

                                            <input readonly type="text" class="form-control fw-bold" value="{{ $sendTo }}">
                                        </div>
                                    </div>

                                    @if ($transferStock?->receiverWarehouse)
                                        <div class="input-group">
                                            <label class="col-5"><b>{{ __('Receive At') }}</b></label>
                                            <div class="col-7">
                                                @php
                                                    $sendAt = $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse?->warehouse_code . ')-(WH)';
                                                @endphp

                                                <input readonly type="text" class="form-control fw-bold" value="{{ $sendAt }}">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-5"><b>{{ __('Received Date') }}</b></label>
                                        <div class="col-7">
                                            <input readonly type="text" name="receive_date" class="form-control fw-bold" id="receive_date" value="{{ $transferStock?->receive_date ? date($generalSettings['business_or_shop__date_format'], strtotime($transferStock->receive_date)) : date($generalSettings['business_or_shop__date_format']) }}" data-next="search_product" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card mb-1 p-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <div class="sale-item-sec">
                                            <div class="sale-item-inner">
                                                <div class="table-responsive">
                                                    <table class="display data__table table sale-product-table">
                                                        <thead class="staky">
                                                            <tr>
                                                                <th class="text-start">{{ __('Product') }}</th>
                                                                <th class="text-start">{{ __('Unit') }}</th>
                                                                <th class="text-start">{{ __('Store Location') }}</th>
                                                                <th class="text-start">{{ __('Send Qty') }}</th>
                                                                <th class="text-start">{{ __('Received Qty') }}</th>
                                                                <th class="text-start">{{ __('Pending Qty') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="transfer_product_list">

                                                            @foreach ($transferStock->transferStockProducts as $transferStockProduct)
                                                                @php
                                                                    $variantName = $transferStockProduct?->variant ? ' - ' . $transferStockProduct?->variant?->variant_name : '';
                                                                    $variantId = $transferStockProduct->variant_id ? $transferStockProduct->variant_id : 'noid';
                                                                @endphp
                                                                <tr id="select_item">
                                                                    <td class="text-start">
                                                                        <span class="product_name">{{ $transferStockProduct->product->name . $variantName }}</span>
                                                                        <input type="hidden" id="unit_cost_inc_tax" value="{{ $transferStockProduct->unit_cost_inc_tax }}">
                                                                        <input type="hidden" name="transfer_stock_product_ids[]" value="{{ $transferStockProduct->id }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span class="fw-bold">{{ $transferStockProduct?->unit?->name }}</span>
                                                                    </td>

                                                                    <td class="text-start fw-bold">
                                                                        @php
                                                                            $storeLocation = $transferStock?->receiverWarehouse?->warehouse_name . '-(' . $transferStock?->receiverWarehouse?->warehouse_code . ')-(WH)';
                                                                        @endphp
                                                                        {{ $storeLocation }}
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_send_qty" class="fw-bold">{{ $transferStockProduct->send_qty }}</span>
                                                                        <input type="hidden" id="send_qty" value="{{ $transferStockProduct->send_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <input type="number" step="any" name="received_quantities[]" class="form-control text-success" id="received_qty" value="{{ $transferStockProduct->received_qty }}">
                                                                    </td>

                                                                    <td class="text-start">
                                                                        <span id="span_pending_qty" class="fw-bold text-danger">{{ $transferStockProduct->pending_qty }}</span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="form_element rounded my-1">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Received Stock Value') }}</b></label>
                                        <div class="col-8">
                                            <input readonly type="text" step="any" name="received_stock_value" class="form-control fw-bold" id="received_stock_value" value="{{ $transferStock->received_stock_value }}" tabindex="-1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>{{ __('Receiver Note') }}</b></label>
                                        <div class="col-8">
                                            <input name="receiver_note" type="text" class="form-control" id="receiver_note" data-next="save_changes" value="{{ $transferStock->receiver_note }}" placeholder="{{ __('Receiver Note') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-loading">
                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i> <span>{{ __('Loading') }}...</span> </button>
                                <button type="button" id="save_changes" class="btn btn-success submit_button">{{ __('Comfirm') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('input', '#received_qty', function(event) {

            var receivedQty = $(this).val() ? $(this).val() : 0;

            var tr = $(this).closest('tr');
            var sendQty = tr.find('#send_qty').val() ? tr.find('#send_qty').val() : 0;

            if (receivedQty > sendQty) {

                $(this).val(parseFloat(sendQty).toFixed(2));
                toastr.error("{{ __('Received quantity must not be greater then send quantity.') }}");
                $('#span_pending_qty').html(parseFloat(0).toFixed(2));
                calculateTotalAmount();
                return;
            }

            var calcPendingQty = parseFloat(sendQty) - parseFloat(receivedQty);
            tr.find('#span_pending_qty').html(parseFloat(calcPendingQty).toFixed(2));
            calculateTotalAmount();
        });

        function calculateTotalAmount() {

            var unitCostsIncTax = document.querySelectorAll('#unit_cost_inc_tax');
            var recevedQuantities = document.querySelectorAll('#received_qty');
            // Update Total Item
            var receivedStockValue = 0;
            var i = 0;
            recevedQuantities.forEach(function(qty) {

                var receivedQty = qty.value ? qty.value : 0;
                var unitCostIncTax = unitCostsIncTax[i].value ? unitCostsIncTax[i].value : 0;
                receivedStockValue += parseFloat(receivedQty) * parseFloat(unitCostIncTax);
                i++;
            });

            $('#received_stock_value').val(parseFloat(receivedStockValue).toFixed(2));
        }

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.submit_button').prop('type', 'button');
        });

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            }
        });

        document.onkeyup = function() {

            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.ctrlKey && e.which == 13) {

                $('#save_changes').click();
                return false;
            } else if (e.which == 27) {

                $('.select_area').hide();
                $('#list').empty();

                return false;
            }
        }

        $('#receive_from_branch_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    $('.loading_button').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                    }

                    toastr.success(data);
                    window.location = "{{ url()->previous() }}";
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error("{{ __('Please check again all form fields.') }}", "{{ __('Some thing went wrong.') }}");

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });

        $(document).on('change keypress', 'input', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 13) {

                $('#' + nextId).focus().select();
            }
        });
    </script>
@endpush
