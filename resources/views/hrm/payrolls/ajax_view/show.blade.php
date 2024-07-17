@php
    $dateFormat = $generalSettings['business_or_shop__date_format'];
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $previousRouteName = app('router')
        ->getRoutes()
        ->match(app('request')->create(url()->previous()))
        ->getName();
@endphp
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">
                    {{ __('Payroll Details') }} ({{ __('Voucher No') }} : <strong>{{ $payroll->voucher_no }}</strong>)
                </h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Employee') }} : - </strong></li>
                            <li style="font-size:11px!important;"><strong>{{ __('Name') }} : </strong> {{ $payroll?->user?->prefix . '  ' . $payroll?->user?->name . '  ' . $payroll?->user?->last_name }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }}: </strong> {{ $payroll->user->phone }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong> {{ $payroll->user->current_address }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __('Month') }} : </strong> {{ $payroll->month . '-' . $payroll->year }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Generated On') }} : </strong> {{ date($dateFormat, strtotime($payroll->date_ts)) }}</li>
                            <li style="font-size:11px!important;"><strong>{{ __('Payroll Voucher No') }} : </strong> {{ $payroll->voucher_no }}</li>

                            <li style="font-size:11px!important;"><strong>{{ __('Payment Status') }} : </strong>
                                @php
                                    $payable = $payroll->gross_amount;
                                @endphp
                                @if ($payroll->due <= 0)
                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                @elseif($payroll->due > 0 && $payroll->due < $payable)
                                    <span class="badge bg-primary text-white">{{ __('Partial') }}</span>
                                @elseif($payable == $payroll->due)
                                    <span class="badge bg-danger text-white">{{ __('Due') }}</span>
                                @endif
                            </li>

                            <li style="font-size:11px!important;">
                                <strong>{{ __('Created By') }} : </strong>
                                {{ $payroll?->createdBy?->prefix . ' ' . $payroll?->createdBy?->name . ' ' . $payroll?->createdBy?->last_name }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ location_label() }} : </strong>
                                @php
                                    $branchName = '';
                                    if ($payroll->branch_id) {
                                        if ($payroll?->branch?->parentBranch) {
                                            $branchName = $payroll?->branch?->parentBranch?->name . '(' . $payroll?->branch?->area_name . ')' . '-(' . $payroll?->branch?->branch_code . ')';
                                        } else {
                                            $branchName = $payroll?->branch?->name . '(' . $payroll?->branch?->area_name . ')' . '-(' . $payroll?->branch?->branch_code . ')';
                                        }
                                    } else {
                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                    }
                                @endphp
                                {{ $branchName }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong>
                                @if ($payroll->branch)
                                    {{ $payroll->branch->phone }}
                                @else
                                    {{ $generalSettings['business_or_shop__phone'] }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <p class="fw-bold">{{ __('Allowances') }}</p>
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($payroll->allowances as $allowance)
                                        <tr>
                                            @php
                                                $name = $allowance?->allowance ? $allowance?->allowance?->name : $allowance->allowance_name;
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $name }}</td>

                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                @php
                                                    $allowanceAmountType = $allowance->amount_type == 2 ? '(' . $allowance->allowance_percent . '%)=' : '';
                                                @endphp
                                                {{ $allowanceAmountType . App\Utils\Converter::format_in_bdt($allowance->allowance_amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __('Total Allowance') }} : </td>
                                        <td colspan="2" class="fw-bold" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <p class="fw-bold">{{ __('Deductions') }}</p>
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('S/L') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Name') }}</th>
                                        <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="purchase_product_list">
                                    @foreach ($payroll->deductions as $deduction)
                                        <tr>
                                            @php
                                                $name = $deduction?->deduction ? $deduction?->deduction?->name : $deduction->deduction_name;
                                            @endphp

                                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>

                                            <td class="text-start" style="font-size:11px!important;">{{ $name }}</td>

                                            <td class="text-start fw-bold" style="font-size:11px!important;">
                                                @php
                                                    $deductionAmountType = $deduction->amount_type == 2 ? '(' . $deduction->deduction_percent . '%)=' : '';
                                                @endphp
                                                {{ $deductionAmountType . App\Utils\Converter::format_in_bdt($deduction->deduction_amount) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bold text-end" style="font-size:11px!important;">{{ __('Total Deduction') }} : </td>
                                        <td colspan="2" class="fw-bold" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <p class="fw-bold">{{ __('Payments') }}</p>
                        @include('hrm.payrolls.ajax_view.partials.payroll_details_payment_list')
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tr>
                                    <th class="text-end">{{ __('Total Amount') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Allowance') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_allowance) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Total Deduction') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->total_deduction) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Gross Amount') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }} </th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->gross_amount) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Paid') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->paid) }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-end">{{ __('Due') }} : {{ $payroll?->branch?->currency?->value ?? $generalSettings['business_or_shop__currency_symbol'] }}</th>
                                    <td class="text-end">
                                        {{ App\Utils\Converter::format_in_bdt($payroll->due) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="m-0 mt-3">

                <div class="row g-0 mt-1">
                    <div class="col-md-6 offset-6">
                        <div class="input-group p-0">
                            <label class="col-4 text-end pe-1 offset-md-6"><b>{{ __('Print') }}</b></label>
                            <div class="col-2">
                                <select id="print_page_size" class="form-control">
                                    @foreach (array_slice(\App\Enums\PrintPageSize::cases(), 0, 2) as $item)
                                        <option {{ $generalSettings['print_page_size__payroll_voucher_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-box">
                            @if (auth()->user()->branch_id == $payroll->branch_id)
                                @can('payrolls_edit')
                                    <a href="{{ route('hrm.payrolls.edit', $payroll->id) }}" class="btn btn-sm btn-secondary">{{ __('Edit') }}</a>
                                @endcan
                            @endif

                            @php
                                $filename = __('Payroll') . '__' . $payroll->voucher_no . '__' . $payroll->month . '-' . $payroll->year . '__' . $branchName;
                            @endphp

                            <a href="{{ route('hrm.payrolls.print', $payroll->id) }}" onclick="printPayroll(this); return false;" class="footer_btn btn btn-sm btn-success" id="printPayrollBtn" data-filename="{{ $filename }}">{{ __('Print') }}</a>
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function printPayroll(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
        var print_page_size = $('#print_page_size').val();
        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                print_page_size
            },
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                    footer: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }
</script>
