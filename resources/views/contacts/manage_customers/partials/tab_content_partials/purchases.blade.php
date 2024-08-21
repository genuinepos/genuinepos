<div class="tab_contant purchases d-hide">
    <div class="row">
        <div class="col-sm-12 col-lg-4" id="for_purchases">
            @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
        </div>

        <div class="col-sm-12 col-lg-8">
            <div class="account_summary_area">
                <div class="heading py-1">
                    <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                </div>

                <div class="account_summary_table">
                    <form id="filter_purchases" method="get" class="px-2">
                        <div class="form-group row align-items-end justify-content-end g-3">
                            <div class="col-lg-9 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ location_label() }}</strong></label>
                                        <select name="branch_id" class="form-control select2" id="purchases_branch_id" autofocus>
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

                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('Payment Status') }}</strong></label>
                                        <select name="payment_status" id="purchases_payment_status" class="form-control">
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
                                            <input type="text" name="from_date" id="purchases_from_date" class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <label><strong>{{ __('To Date') }}</strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>

                                            <input type="text" name="to_date" id="purchases_to_date" class="form-control" autocomplete="off">
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
                                        <a href="#" class="btn btn-sm btn-primary float-end" id="printPurchasesReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
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
                    <table id="purchases-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('P.Invoice ID') }}</th>
                                <th>{{ location_label() }}</th>
                                <th>{{ __('Supplier') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                <th>{{ __('Total Purchased Amount') }}</th>
                                <th>{{ __('Paid') }}</th>
                                <th>{{ __('Return') }}</th>
                                <th>{{ __('Due') }}</th>
                                <th>{{ __('Created By') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="6" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th id="purchase_total_purchase_amount" class="text-white"></th>
                                <th id="purchase_paid" class="text-white"></th>
                                <th id="purchase_purchase_return_amount" class="text-white"></th>
                                <th id="purchase_due" class="text-white"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
