<div class="tab_contant ledger">
    <div class="row">
        <div class="col-sm-12 col-lg-3" id="for_ledger">
            @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
        </div>

        <div class="col-sm-12 col-lg-9">
            <div class="account_summary_area">
                <div class="heading py-1">
                    <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                </div>

                <div class="account_summary_table">
                    <form id="filter_customer_ledgers" method="get" class="px-2">
                        <div class="form-group row align-items-end g-3">
                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ location_label() }}</strong></label>
                                <select name="branch_id" class="form-control select2" id="ledger_branch_id" autofocus>
                                    @if (!$branch)
                                        <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Company') }})</option>
                                    @else
                                        @php
                                            $branchName = $branch->parent_branch_id ? $branch->parentBranch?->name : $branch->name;
                                            $areaName = $branch->area_name ? '(' . $branch->area_name . ')' : '';
                                            $branchCode = '-' . $branch->branch_code;
                                        @endphp

                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="">{{ __('All') }}</option>
                                        <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $branch->id }}">
                                            {{ $branchName . $areaName . $branchCode }}
                                        </option>
                                        @if (count($branch->childBranches) > 0)
                                            @foreach ($branch->childBranches as $childBranch)
                                                @php
                                                    $branchName = $branch->name;
                                                    $areaName = '(' . $branch->area_name . ')';
                                                    $branchCode = '-' . $childBranch->branch_code;
                                                @endphp
                                                <option data-branch_name="{{ $branchName . $areaName . $branchCode }}" value="{{ $childBranch->id }}">
                                                    {{ $branchName . $areaName . $branchCode }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('From Date') }}</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                    </div>
                                    <input type="text" name="from_date" id="ledger_from_date" class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('To Date') }}</strong></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                    </div>

                                    <input type="text" name="to_date" id="ledger_to_date" class="form-control" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('Note/Remarks') }} :</strong></label>
                                <select name="note" class="form-control" id="ledger_note">
                                    <option value="0">{{ __('No') }}</option>
                                    <option selected value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('Voucher Details') }} :</strong></label>
                                <select name="voucher_details" class="form-control" id="ledger_voucher_details">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('Transaction Details') }} :</strong></label>
                                <select name="transaction_details" class="form-control" id="ledger_transaction_details">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <label><strong>{{ __('Inventory List') }} :</strong></label>
                                <select name="inventory_list" class="form-control" id="ledger_inventory_list">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <div class="row align-items-end">
                                    <div class="col-6">
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <a href="#" class="btn btn-sm btn-primary float-end" id="printLedger"><i class="fas fa-print"></i> {{ __('Print') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="ledger_table_area">
                <div class="table-responsive" id="payment_list_table">
                    <table id="ledger-table" class="display data_tbl data__table ledger_table common-reloader w-100">
                        <thead>
                            <tr>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Particulars') }}</th>
                                <th>{{ __('Voucher Type') }}</th>
                                <th>{{ __('Voucher No') }}</th>
                                <th>{{ __('Debit') }}</th>
                                <th>{{ __('Credit') }}</th>
                                <th>{{ __('Running Balance') }}</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="4" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                <th id="ledger_table_total_debit" class="text-white text-end"></th>
                                <th id="ledger_table_total_credit" class="text-white text-end"></th>
                                <th id="ledger_table_current_balance" class="text-white text-end"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
