<style>
    .payment_top_card {
        background: #d7dfe8;
    }

    .payment_top_card span {
        font-size: 12px;
        font-weight: 400;
    }

    .payment_top_card li {
        font-size: 12px;
    }

    .payment_top_card ul {
        padding: 6px;
        border: 1px solid #dcd1d1;
    }

    .payment_list_table {
        position: relative;
    }

    .payment_details_contant {
        background: azure !important;
    }

    h6.checkbox_input_wrap {
        border: 1px solid #495677;
        padding: 0px 7px;
    }

    .table-responsive {
        min-height: 162px;
    }
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Money Receipt List') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        @php
            $accountBalanceService = new App\Services\Accounts\AccountBalanceService();
            $amounts = $accountBalanceService->accountBalance(accountId: $contact?->account?->id, fromDate: null, toDate: null, branchId: null);
        @endphp

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Shop') }}</th>
                                <td class="text-start" style="font-size:11px!important"> :
                                    @if ($contact?->account?->branch)
                                        {{ $contact?->account?->branch?->name }}
                                    @else
                                        {{ $generalSettings['business__business_name'] }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Customer') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->name }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Business') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->business_name }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Phone') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->phone }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Address') }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->address }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Current Balance') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['closing_balance_string'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Opening Balance') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['opening_balance_in_flat_amount_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Sale') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_sale_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Purchase') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_purchase_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Return') }}</th>
                                <td class="text-start fw-bold" style="font-size:11px!important"> : {{ $amounts['total_return_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Received') }}</th>
                                <td class="text-start fw-bold text-success" style="font-size:11px!important"> : {{ $amounts['total_received_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __('Total Paid') }}</th>
                                <td class="text-start fw-bold text-danger" style="font-size:11px!important"> : {{ $amounts['total_paid_string'] }}</td>
                            </tr>

                            <tr>
                                <th class="text-start text-danger" style="font-size:11px!important">{{ __('Curr. Balance') }}</th>
                                <td class="text-start fw-bold text-danger" style="font-size:11px!important"> : {{ $amounts['closing_balance_in_flat_amount_string'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="receipt_list_table mt-2">
                <div class="table-responsive">
                    <table class="display data_tbl data__table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Shop/Business') }}</th>
                                <th>{{ __('Voucher No') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="receipt_list_body">
                            @if (count($contact->moneyReceiptsOfOwnBranch) > 0)
                                @foreach ($contact->moneyReceiptsOfOwnBranch as $receipt)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($receipt->date_ts)) }}</td>

                                        <td>
                                            @if ($receipt?->branch)
                                                @if ($receipt?->branch?->parentBranch)
                                                    {{ $receipt?->branch?->parentBranch?->name }}({{ $receipt->branch->area_name }})
                                                @else
                                                    {{ $receipt?->branch?->name }}({{ $receipt->branch->area_name }})
                                                @endif
                                            @else
                                                {{ $generalSettings['business__business_name'] }}({{ __('Business') }})
                                            @endif
                                        </td>

                                        <td>{{ $receipt->voucher_no }}</td>

                                        <td class="fw-bold">
                                            {{ App\Utils\Converter::format_in_bdt($receipt->amount) }}
                                        </td>

                                        <td>
                                            {{ $receipt->status }}
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Action') }}</button>

                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item" onclick="printMoneyReceipt(this); return false;" href="{{ route('contacts.money.receipts.print', $receipt->id) }}">{{ __('Print') }}</a>

                                                    @can('money_receipt_edit')
                                                        <a class="dropdown-item" onclick="editMoneyReceipt(this); return false;" href="{{ route('contacts.money.receipts.edit', $receipt->id) }}">{{ __('Edit') }}</a>
                                                    @endcan

                                                    @can('money_receipt_delete')
                                                        <a class="dropdown-item" onclick="deleteMoneyReceipt(this); return false;" href="{{ route('contacts.money.receipts.delete', $receipt->id) }}">{{ __('Delete') }}</a>
                                                    @endcan
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('No Data Found.') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                    @can('money_receipt_add')
                        <a href="{{ route('contacts.money.receipts.create', [$contact->id]) }}" onclick="addMoneryReceiptVoucher(this); return false;" class="btn btn-sm btn-success">{{ __('Generate New') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function editMoneyReceipt(event) {

        var url = $(event).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#moneyReciptAddOrEditModal').html(data);
                $('#moneyReciptAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#mr_amount').focus().select();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    };

    function addMoneryReceiptVoucher(event) {

        var url = $(event).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#moneyReciptAddOrEditModal').html(data);
                $('#moneyReciptAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#mr_amount').focus();
                }, 500);
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    var deleteAbleMoneryReceiptVoucherTr = '';

    function deleteMoneyReceipt(event) {

        var url = $(event).attr('href');
        deleteAbleMoneryReceiptVoucherTr = $(event).closest('tr');

        $('#delete_money_receipt_form').attr('action', url);

        $.confirm({
            'title': 'Confirmation',
            'message': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#delete_money_receipt_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-modal-primary',
                    'action': function() {
                        console.log('Deleted canceled.');
                    }
                }
            }
        });
    }

    function printMoneyReceipt(event) {

        var url = $(event).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 500,
                    header: null,
                });
                return;
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    };
</script>
