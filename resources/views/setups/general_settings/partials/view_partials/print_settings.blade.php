<form id="print_page_size_settings_form" class="setting_form hide-all" action="{{ route('settings.print.page.size.settings') }}" method="post">
    @csrf
    <div class="form-group">
        <div class="setting_form_heading">
            <h6 class="text-primary">{{ __('Print Settings') }}</h6>
        </div>
    </div>

    <div class="form-group row mt-3">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Add Sale') }}</label>
                        <div class="col-md-9">
                            <select name="add_sale_page_size" class="form-control" id="add_sale_page_size">
                                @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                    <option @selected($generalSettings['print_page_size__add_sale_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('pos Sale') }}</label>
                        <div class="col-md-9">
                            <select name="pos_sale_page_size" class="form-control" id="pos_sale_page_size">
                                @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                    <option @selected($generalSettings['print_page_size__pos_sale_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Quotation') }}</label>
                        <div class="col-md-9">
                            <select name="quotation_page_size" class="form-control" id="quotation_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__quotation_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Sales Order') }}</label>
                        <div class="col-md-9">
                            <select name="sales_order_page_size" class="form-control" id="sales_order_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__sales_order_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Draft') }}</label>
                        <div class="col-md-9">
                            <select name="draft_page_size" class="form-control" id="draft_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__draft_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Sales Return') }}</label>
                        <div class="col-md-9">
                            <select name="sales_return_page_size" class="form-control" id="sales_return_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__sales_return_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase') }}</label>
                        <div class="col-md-9">
                            <select name="purchase_page_size" class="form-control" id="purchase_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__purchase_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase Order') }}</label>
                        <div class="col-md-9">
                            <select name="purchase_order_page_size" class="form-control" id="purchase_order_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__purchase_order_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Purchase Return') }}</label>
                        <div class="col-md-9">
                            <select name="purchase_return_page_size" class="form-control" id="purchase_return_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__purchase_return_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Transfer Stock') }}</label>
                        <div class="col-md-9">
                            <select name="transfer_stock_voucher_page_size" class="form-control" id="transfer_stock_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__transfer_stock_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('S. Adjustment') }}</label>
                        <div class="col-md-9">
                            <select name="stock_adjustment_voucher_page_size" class="form-control" id="stock_adjustment_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__stock_adjustment_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Receipt Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="receipt_voucher_page_size" class="form-control" id="receipt_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__receipt_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payment Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="payment_voucher_page_size" class="form-control" id="payment_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__payment_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Expense Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="expense_voucher_page_size" class="form-control" id="expense_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__expense_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Contra Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="contra_voucher_page_size" class="form-control" id="contra_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__contra_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payroll Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="payroll_voucher_page_size" class="form-control" id="payroll_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__payroll_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Payroll Payment') }}</label>
                        <div class="col-md-9">
                            <select name="payroll_payment_voucher_page_size" class="form-control" id="payroll_payment_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__payroll_payment_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('BOM Voucher') }}</label>
                        <div class="col-md-9">
                            <select name="bom_voucher_page_size" class="form-control" id="bom_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__bom_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-1">
                    <div class="input-group">
                        <label class="col-md-3 text-end fw-bold pe-1">{{ __('Production Vch.') }}</label>
                        <div class="col-md-9">
                            <select name="production_voucher_page_size" class="form-control" id="production_voucher_page_size">
                                @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                    <option @selected($generalSettings['print_page_size__production_voucher_page_size'] == $item->value) value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="btn-loading">
                <button type="button" class="btn loading_button print_setting_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                <button class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</form>
