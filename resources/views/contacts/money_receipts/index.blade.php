<style>
    .payment_top_card {background: #d7dfe8;}
    .payment_top_card span {font-size: 12px;font-weight: 400;}
    .payment_top_card li {font-size: 12px;}
    .payment_top_card ul {padding: 6px;border: 1px solid #dcd1d1;}
    .payment_list_table {position: relative;}
    .payment_details_contant{background: azure!important;}
    h6.checkbox_input_wrap {border: 1px solid #495677;padding: 0px 7px;}
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __("Money Receipt List") }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Customer") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->name }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Phone") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->phone }}</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Business") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : {{ $contact->business_name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="payment_top_card">
                        <table class="table table-sm display modal-table">
                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Total Sale") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Total Purchase") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Total Return") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Total Received") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Total Paid") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>

                            <tr>
                                <th class="text-start" style="font-size:11px!important">{{ __("Curr. Balance") }}</th>
                                <td class="text-start" style="font-size:11px!important"> : 0.00</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="receipt_list_table mt-2">
                <div class="data_preloader receipt_list_preloader">
                    <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                </div>
                <div class="table-responsive">
                    <div class="data_preloader receipt_preloader">
                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')...</h6>
                    </div>
                    <table class="display data_tbl data__table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __("Date") }}</th>
                                <th>{{ __("Shop") }}</th>
                                <th>{{ __("Voucher No") }}</th>
                                <th>{{ __("Amount") }}</th>
                                <th>{{ __("Status") }}</th>
                                <th>{{ __("Action") }}</th>
                            </tr>
                        </thead>
                        <tbody id="receipt_list_body">
                            @if (count($contact->moneyReceiptsOfOwnBranch) > 0)
                                @foreach ($contact->moneyReceiptsOfOwnBranch as $receipt)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($receipt->date_ts)) }}</td>

                                        <td>
                                            @if ($receipt->branch)

                                                {{ $receipt->branch->name }}/{{ $receipt->branch->branch_code }}
                                            @else

                                                @lang('menu.head_office')
                                            @endif
                                        </td>

                                        <td>{{ $receipt->invoice_id }}</td>

                                        <td>
                                            {{ App\Utils\Converter::format_in_bdt($receipt->amount) }}
                                        </td>

                                        <td>
                                            {{ $receipt->status }}
                                        </td>

                                        <td>
                                            <div class="btn-group" role="group">
                                                <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('menu.action')</button>

                                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <a class="dropdown-item" id="print_receipt" href="{{ route('money.receipt.voucher.print', $receipt->id) }}"><i class="fas fa-print text-primary"></i>@lang('menu.print')</a>
                                                    <a class="dropdown-item" id="edit_receipt" href="{{ route('money.receipt.voucher.edit', $receipt->id) }}"><i class="fas fa-edit text-primary"></i>@lang('menu.edit')</a>
                                                    <a class="dropdown-item" id="change_receipt_status" href="{{ route('money.receipt.voucher.status.change.modal', $receipt->id) }}"><i class="far fa-file-alt text-primary"></i>@lang('menu.change_status')</a>
                                                    <a class="dropdown-item" id="delete_receipt" href="{{ route('money.receipt.voucher.delete', $receipt->id) }}"><i class="far fa-trash-alt text-primary"></i> @lang('menu.delete')</a>
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
                    <form id="receipt_deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>

            <div class="form-group row mt-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                    <a href="{{ route('contacts.money.receipts.create', [$contact->id]) }}" id="add_monery_receipt" class="btn btn-sm btn-success">{{ __('Generate New') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
