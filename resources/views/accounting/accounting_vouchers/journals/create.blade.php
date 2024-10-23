@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

        #journal_account_list tr {
            cursor: pointer;
        }

        #cost_centre_table_row_list td {
            line-height: 15px;
        }

        #cost_centre_table_row_list tr {
            cursor: pointer;
        }

        .cost_centre_list_for_entry_table_area table tbody tr td {
            line-height: 1px !important;
            height: 14px;
            font-size: 12px !important;
        }

        .selected_account {
            background-color: #746e70 !important;
            color: #fff !important;
            padding: 0px 3px;
            font-weight: 600;
            display: block;
        }

        .selected_cost_centre {
            background-color: #746e70 !important;
            color: #fff !important;
            padding: 0px 3px;
            font-weight: 600;
            display: block;
        }

        ul.list-unstyled.account_list {
            min-height: 63.4vh;
            max-height: 63.4vh;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1px 2px;
        }

        ul.list-unstyled.cost_centre_list {
            min-height: 361px;
            max-height: 361px;
            overflow-y: scroll;
            overflow-x: hidden;
            padding: 1px 2px;
        }

        .spinner_hidden::-webkit-outer-spin-button,
        .spinner_hidden::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        .spinner_hidden input[type=number] {
            -moz-appearance: textfield;
        }

        .cost_centre_table_area {
            height: 400px;
            border: 1px solid black;
            overflow: auto;
        }

        /* .cost_centre_table_area table th { background: #0f2f5e!important; box-shadow: 0px 0px 0 2px #e8e8e8!important; } */
        /* TEST */

        .journal_entry_table_area input {
            font-size: 12px;
        }

        .spinner_hidden {
            border: 1px solid #fff;
        }

        .curr_bl {
            font-size: 10px;
        }

        a.select_account {
            font-size: 11px;
            letter-spacing: 1px;
        }

        ul.account_list li {
            border-bottom: 1px solid #d1c6c6;
        }

        a.select_cost_centre {
            font-size: 10px;
            letter-spacing: 1px;
        }

        ul.cost_centre_list li {
            border-bottom: 1px solid #d1c6c6;
        }

        .table_tr_remove_btn:focus {
            box-sizing: border-box;
            box-shadow: 0 0 0 0.18rem rgb(231 49 49 / 50%);
            border: none;
            padding: 0px 1px;
            border-radius: 2px;
        }

        .table_tr_add_btn:focus {
            box-sizing: border-box;
            box-shadow: 0 0 0 0.18rem rgb(49 231 71 / 59%);
            border: none;
            padding: 0px 1px;
            border-radius: 2px;
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

        .btn-sale {
            width: calc(50% - 4px);
            padding-left: 0;
            padding-right: 0;
        }

        .sale-item-sec {
            height: 64vh;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
@endpush
@section('title', 'Add Journal - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name g-0">
                <div class="col-md-7">
                    <div class="name-head">
                        <h6>{{ __('Add Journal') }}</h6>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="row g-0">
                        <div class="col-md-10">
                            <div class="input-group">
                                <label class="col-4 offset-md-6"><b>{{ __('Print') }}</b></label>
                                <div class="col-2">
                                    <select id="select_print_page_size" class="form-control">
                                        @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                            <option {{ $generalSettings['print_page_size__add_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button d-inline"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-1">
            <form id="add_sale_form" action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="action" id="action">
                <section>
                    <div class="sale-content">
                        <div class="row g-1">
                            <div class="col-md-9">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body p-1">
                                        <div class="row g-1">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <label class="col-md-3 text-end pe-2"><b>{{ __('Date') }}</b> <span class="text-danger">*</span></label>
                                                    <div class="col-9">
                                                        <input type="text" name="date" id="date" class="form-control" value="{{ date($generalSettings['business_or_shop__date_format']) }}" data-next="is_transaction_details" placeholder="{{ __('Date') }}" autocomplete="off">
                                                        <span class="error error_date"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-md-6 text-end pe-2"><b>{{ __('Add Transaction Details') }}</b></label>
                                                    <div class="col-6">
                                                        <select name="is_transaction_details" class="form-control" id="is_transaction_details" data-next="maintain_cost_centre">
                                                            <option value="1">{{ __('Yes') }}</option>
                                                            <option value="0">{{ __('No') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <label class="col-md-6 text-end pe-2"><b>{{ __('Reference Docs') }}</b></label>
                                                    <div class="col-6">
                                                        <input type="file" name="reference_docs[]" class="form-control" id="against_invoice"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body py-0">
                                        <div class="row mt-1">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner journal_entry_table_area">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table sale-product-table">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th class="text-start">{{ __('Description') }}</th>
                                                                    <th class="text-end">{{ __('Debit') }}</th>
                                                                    <th class="text-end">{{ __('Credit') }}</th>
                                                                    <th class="text-center">...</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="journal_account_list">
                                                                <tr>
                                                                    <td>
                                                                        <div class="row py-1">
                                                                            <div class="col-2">
                                                                                <input readonly type="text" name="amount_types[]" id="amount_type" maxlength="2" class="form-control fw-bold" value="Dr" tabindex="-1">
                                                                            </div>

                                                                            <div class="col-6">
                                                                                <input type="text" data-only_type="all" class="form-control fw-bold" id="search_account" autocomplete="off">
                                                                                <input type="hidden" id="account_name" class="voidable">
                                                                                <input type="hidden" id="default_account_name" class="voidable">
                                                                                <input type="hidden" name="account_ids[]" id="account_id" class="voidable">
                                                                                <input type="hidden" name="payment_method_ids[]" id="payment_method_id" class="voidable">
                                                                                <input type="hidden" name="transaction_nos[]" id="transaction_no" class="voidable">
                                                                                <input type="hidden" name="cheque_nos[]" id="cheque_no" class="voidable">
                                                                                <input type="hidden" name="cheque_serial_nos[]" id="cheque_serial_no" class="voidable">
                                                                                <input type="hidden" name="indexes[]" id="index" value="0">
                                                                                <input type="hidden" name="journal_entry_ids[]" id="journal_entry_id" value="">
                                                                                @php
                                                                                    $uniqueId = uniqid();
                                                                                @endphp
                                                                                <input type="hidden" class="unique_id-{{ $uniqueId }}" id="unique_id" value="{{ $uniqueId }}">
                                                                                <input type="hidden" id="main_group_number" class="voidable">
                                                                                </div>

                                                                                <div class="col-4">
                                                                                    <p class="fw-bold text-muted curr_bl">{{ __('Curr. Bal.') }} :
                                                                                        <span id="account_balance" class="fw-bold text-dark voidable"></span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <p class="m-0 p-0 fw-bold" id="show_debit_amount"></p>
                                                                        <input type="number" step="any" name="debit_amounts[]" class="form-control fw-bold spinner_hidden d-hide text-end" id="debit_amount" value="0.00">
                                                                    </td>

                                                                    <td>
                                                                        <p class="m-0 p-0 fw-bold" id="show_credit_amount"></p>
                                                                        <input type="number" step="any" name="credit_amounts[]" class="form-control fw-bold spinner_hidden d-hide text-end" id="credit_amount" value="0.00">
                                                                    </td>

                                                                    <td>
                                                                        <div class="row g-0">
                                                                            <div class="col-md-6">
                                                                                <a href="#" onclick="return false;" tabindex="-1" class="d-inline"><i class="fas fa-trash-alt text-secondary mt-1"></i></a>
                                                                            </div>

                                                                            <div class="col-md-6">
                                                                                <a href="#" id="add_entry_btn" class="table_tr_add_btn ms-1 d-inline"><i class="fa-solid fa-plus text-success mt-1"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>

                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-end fw-bold" colspan="1">{{ __('Total') }}:</td>
                                                                    <td class="text-end fw-bold" id="show_debit_total">0.00</td>
                                                                    <td class="text-end fw-bold" id="show_credit_total">0.00</td>
                                                                    <td class="text-center">...</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row gx-2 gy-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label class="col-1 text-end pe-1"><b>{{ __('Remarks') }} </b></label>
                                                    <div class="col-11">
                                                        <input type="hidden" name="debit_total" id="debit_total" value="0">
                                                        <input type="hidden" name="credit_total" id="credit_total" value="0">
                                                        <input type="text" name="remarks" class="form-control" id="remarks" data-next="shipment_address" placeholder="{{ __('Remarks') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form_element rounded m-0 p-2 text-end">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h6 class="fw-bold">{{ __('List of Accounts') }}</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="form_element rounded m-0 mt-1">
                                    <div class="element-body side-number-field">
                                        <div class="payment_body">
                                            <div class="row g-2">
                                                <div class="col-md-12">
                                                    <ul class="list-unstyled account_list" id="account_list">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center">
                                            <div class="col-12 d-flex justify-content-end pt-1">
                                                <div class="btn-loading d-flex flex-wrap gap-2 w-100">
                                                    <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i></button>
                                                    <button type="submit" class="btn btn-sale btn-success submit_button">{{ __('Save & Print') }}</button>
                                                    <button type="submit" class="btn btn-sale btn-success submit_button">{{ __('Save') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addTransactionDetailsModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __("Transaction Details") }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <span class="fas fa-times"></span>
                    </a>
                </div>

                <div class="modal-body">
                    <form id="add_transaction_details_form" action="" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label class="col-md-3"><b>{{ __("Type") }} :</b></label>
                                    <div class="col-md-9">
                                        <select id="trans_payment_method_id" class="form-control trans_input form-select">
                                            <option value="">None</option>
                                            @foreach ($methods as $method)
                                                <option value="{{ $method->id }}">{{ $method->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <label class="col-md-3"><b>{{ __("Transaction No") }} :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input" id="trans_transaction_no" placeholder="{{ __("Transaction No") }}">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-md-3"><b>{{ __("Cheque No") }} :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input" id="trans_cheque_no" placeholder="{{ __("Cheque No") }}">
                                    </div>
                                </div>

                                <div class="input-group mt-1">
                                    <label class="col-md-3"><b>{{ __("Cheque Serial No") }} :</b></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control trans_input" id="trans_cheque_serial_no" placeholder="{{ __("Cheque Serial No") }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="search_product">
@endsection
@push('scripts')
    @include('accounting.accounting_vouchers.journals.js_partials.add_js')
@endpush
