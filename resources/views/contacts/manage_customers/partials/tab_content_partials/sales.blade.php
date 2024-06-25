<div class="tab_contant sale d-hide">
    <div class="row">
        <div class="col-sm-12 col-lg-4" id="for_sales">
            @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
        </div>

        <div class="col-sm-12 col-lg-8">
            <div class="account_summary_area">
                <div class="heading py-1">
                    <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                </div>

                <div class="account_summary_table">
                    <form id="filter_sales" method="get" class="px-2">
                        <div class="form-group row align-items-end justify-content-end g-3">
                            <div class="col-lg-9 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('Shop/Business') }}</strong></label>
                                        <select name="branch_id" class="form-control select2" id="sales_branch_id" autofocus>
                                            @if (!$branch)
                                                <option data-branch_name="{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})" value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
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

                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('Payment Status') }}</strong></label>
                                        <select name="payment_status" id="sales_payment_status" class="form-control">
                                            <option value="">{{ __('All') }}</option>
                                            @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                                                <option value="{{ $paymentStatus->value }}">{{ $paymentStatus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('From Date') }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="sales_from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('To Date') }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>

                                            <input type="text" name="to_date" id="sales_to_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <div class="row align-items-end">
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <button type="submit" class="btn text-white btn-sm btn-info float-start"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <a href="#" class="btn btn-sm btn-primary float-end" id="printSalesReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
            <div class="table_area">
                <div class="table-responsive">
                    <table id="sales-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Invoice ID') }}</th>
                                <th>{{ __('Shop') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                <th>{{ __('Total Item') }}</th>
                                <th>{{ __('Total Qty') }}</th>
                                <th>{{ __('Total Invoice Amt') }}</th>
                                <th>{{ __('Received Amount') }}</th>
                                <th>{{ __('Return') }}</th>
                                <th>{{ __('Due') }}</th>
                                <th>{{ __('Created By') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="6" class="text-white text-end">{{ __('Total') }} : ({{ $generalSettings['business_or_shop__currency_symbol'] }})</th>
                                <th id="sales_total_item" class="text-white text-end"></th>
                                <th id="sales_total_qty" class="text-white text-end"></th>
                                <th id="sales_total_invoice_amount" class="text-white text-end"></th>
                                <th id="sales_received_amount" class="text-white text-end"></th>
                                <th id="sales_sale_return_amount" class="text-white text-end"></th>
                                <th id="sales_due" class="text-white text-end"></th>
                                <th class="text-white text-end">---</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
