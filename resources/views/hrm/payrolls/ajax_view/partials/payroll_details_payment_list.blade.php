<style>
    .modal_payments_or_receipts_list_table table td { font-size: 10px!important;}
    .modal_payments_or_receipts_list_table table th { font-size: 10px!important;}
</style>
<div class="modal_payments_or_receipts_list_table">
    <div class="table-responsive">
        <table id="" class="table modal-table table-sm">
            <thead>
                <tr class="text-white">
                    <th>{{ __("Voucher Type") }}</th>
                    <th>{{ __("Date") }}</th>
                    <th>{{ __("Voucher No") }}</th>
                    <th>{{ __("Type") }}</th>
                    <th>{{ __("Account") }}</th>
                    <th>{{ __("Amount") }}</th>
                    @if ($previousRouteName == 'hrm.payrolls.index')
                        <th class="action_hideable">{{ __("Action") }}</th>
                    @endif
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @php
                    $totalPaidAmount = 0;
                    $sortedReferences = $payroll->references->sortByDesc(function ($reference) {
                        return optional($reference->voucherDescription->accountingVoucher)->date_ts;
                    })->values()->all();
                @endphp
                @if (count($sortedReferences) > 0)

                   @foreach ($sortedReferences as $reference)

                        @php
                            $voucherType = '';
                            $cashBankAccount = '';
                            $accountNo = '';
                            $bankBranch = '';
                            $bank = '';
                            $method = '';
                            $date = '';

                            if ($reference?->voucherDescription) {

                                $voucherType = 'Payroll Payment';
                                $date = $reference?->voucherDescription->accountingVoucher?->date;
                                $descriptions = $reference?->voucherDescription?->accountingVoucher?->voucherDescriptions;

                                $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {

                                    return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
                                });

                                $cashBankAccount = $filteredCashOrBankAccounts->first();
                                $accountNo = $cashBankAccount->account->account_number ? '/'. substr($cashBankAccount->account->account_number, -4) : '';
                                $bankBranch = $cashBankAccount?->account?->bank_branch ? '('.$cashBankAccount?->account?->bank_branch.')' : '';
                                $bank = $cashBankAccount?->account?->bank ? '-' . $cashBankAccount?->account?->bank->name.$bankBranch : '';
                                $method = $cashBankAccount?->paymentMethod ? $cashBankAccount?->paymentMethod->name : '';
                            }
                        @endphp

                        <tr>
                            <td>{{ $voucherType }}</td>
                            <td>{{ date($generalSettings['business_or_shop__date_format'], strtotime($date)) }}</td>
                            <td>{{ $reference->voucherDescription->accountingVoucher->voucher_no }}</td>
                            <td>{{ $method }}</td>
                            {{-- <td class="fw-bold">{{ $cashBankAccount?->account?->name.$accountNo.$bank }}</td> --}}
                            <td>{{ $cashBankAccount?->account?->name.$accountNo }}</td>

                            <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($reference?->amount) }}</td>
                            @php
                                $totalPaidAmount += $reference?->amount ? $reference?->amount : 0;
                            @endphp

                            @if ($previousRouteName == 'hrm.payrolls.index')
                                <td class="action_hideable">
                                    @if ($reference->voucherDescription)
                                        @if ($reference->voucherDescription->accountingVoucher)
                                            <a href="{{ route('hrm.payroll.payments.show', $reference->voucherDescription->accountingVoucher->id) }}" id="extraDetailsBtn" class="text-info me-1"><i class="fa-regular fa-eye"></i></a>

                                            <a href="{{ route('hrm.payroll.payments.edit', $reference->voucherDescription->accountingVoucher->id) }}" id="editPayment" class="text-warning me-1"><i class="fa-regular fa-pen-to-square"></i></a>

                                            <a href="{{ route('hrm.payroll.payments.delete', $reference->voucherDescription->accountingVoucher->id) }}" id="deletePayment" class="text-danger"><i class="fa-solid fa-trash-can"></i></a>
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                   @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">{{ __("No Date Found") }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">{{ __("Total Paid") }} : </th>
                    <th>{{ App\Utils\Converter::format_in_bdt($totalPaidAmount) }}</th>
                    @if ($previousRouteName == 'hrm.payrolls.index')
                        <th>---</th>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>
