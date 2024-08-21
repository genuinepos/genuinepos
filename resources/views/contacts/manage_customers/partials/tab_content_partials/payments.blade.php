<div class="tab_contant payments d-hide">
    <div class="row">
        <div class="col-sm-12 col-lg-3" id="for_payments">
            @include('contacts.manage_customers.partials.account_summery_area_by_ledgers')
        </div>

        <div class="col-sm-12 col-lg-9">
            <div class="account_summary_area">
                <div class="heading py-1">
                    <h5 class="py-1 pl-1 text-center">{{ __('Filter Area') }}</h5>
                </div>

                <div class="account_summary_table">
                    <div class="row mt-2">
                        <div class="col-md-10">
                            <div class="card pb-5">
                                <form id="filter_payments" class="py-2 px-2 mt-2" method="get">
                                    <div class="form-group row align-items-end">
                                        <div class="col-lg-3 col-md-6">
                                            <label><strong>{{ location_label() }}</strong></label>
                                            <select name="branch_id" class="form-control select2" id="payments_branch_id" autofocus>
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

                                        <div class="col-lg-3 col-md-6">
                                            <label><strong>{{ __('From Date') }}</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="from_date" id="payments_from_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <label><strong>{{ __('To Date') }}</strong></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                                </div>
                                                <input type="text" name="to_date" id="payments_to_date" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <div class="row align-items-end">
                                                <div class="col-md-6">
                                                    <button type="submit" class="btn text-white btn-sm btn-info float-start m-0"><i class="fas fa-funnel-dollar"></i> {{ __('Filter') }}</button>
                                                </div>

                                                <div class="col-md-6">
                                                    <a href="#" class="btn btn-sm btn-primary" id="printPaymentReport"><i class="fas fa-print"></i> {{ __('Print') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @if (auth()->user()->can('payments_create'))
                            <div class="col-md-2 mt-md-0 mt-2">
                                <div class="col-md-12 col-sm-12 col-lg-12 d-md-block d-flex gap-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ route('payments.create', ['debitAccountId' => $contact?->account?->id]) }}" class="btn btn-sm btn-success" id="addPayment"><i class="far fa-money-bill-alt text-white"></i> {{ __('Add Payment') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="widget_content table_area">
                <div class="table-responsive">
                    <table id="payments-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                <th>{{ __('Action') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Voucher') }}</th>
                                <th>{{ location_label() }}</th>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Remarks') }}</th>
                                {{-- <th>{{ __("Received From") }}</th> --}}
                                <th>{{ __('Paid From') }}</th>
                                <th>{{ __('Type/Method') }}</th>
                                <th>{{ __('Trans. No') }}</th>
                                <th>{{ __('Cheque No') }}</th>
                                {{-- <th>{{ __("Cheque S/L No") }}</th> --}}
                                <th>{{ __('Paid Amount') }}</th>
                                {{-- <th>{{ __("Created By") }}</th> --}}
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr class="bg-secondary">
                                <th colspan="10" class="text-end text-white">{{ __('Total') }} : {{ $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                <th id="payments_total_amount" class="text-white"></th>
                                {{-- <th></th> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
