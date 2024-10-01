<style>
    .modal_payments_or_receipts_list_table table td {
        font-size: 10px !important;
    }

    .modal_payments_or_receipts_list_table table th {
        font-size: 10px !important;
    }
</style>
<div class="modal_payments_or_receipts_list_table">
    <div class="table-responsive">
        <table id="" class="table modal-table table-sm">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="text-start">{{ __('Voucher Type') }}</th>
                    <th class="text-start">{{ __('Date') }}</th>
                    <th class="text-start">{{ __('Voucher No') }}</th>
                    <th class="text-start">{{ __('Type') }}</th>
                    <th class="text-start">{{ __('Account') }}</th>
                    <th class="text-start">{{ __('Amount') }}</th>
                    <th class="action_hideable text-start">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @php
                    $totalReceivedAmount = 0;
                @endphp
                @if (count($adjustment->references) > 0)

                    @foreach ($adjustment->references as $reference)
                        @php
                            $voucherType = '';
                            $cashBankAccount = '';
                            $accountNo = '';
                            $bankBranch = '';
                            $bank = '';
                            $method = '';
                            $date = '';

                            if ($reference?->voucherDescription) {
                                $voucherType = 'Receipt';
                                $date = $reference?->voucherDescription->accountingVoucher?->date;
                                $descriptions = $reference?->voucherDescription?->accountingVoucher?->voucherDescriptions;

                                $filteredCashOrBankAccounts = $descriptions->filter(function ($description, $key) {
                                    return $description?->account?->group->sub_sub_group_number == 1 || $description?->account?->group->sub_sub_group_number == 2 || $description?->account?->group->sub_sub_group_number == 11;
                                });

                                $cashBankAccount = $filteredCashOrBankAccounts->first();
                                $accountNo = $cashBankAccount->account->account_number ? '/' . substr($cashBankAccount->account->account_number, -4) : '';
                                $bankBranch = $cashBankAccount?->account?->bank_branch ? '(' . $cashBankAccount?->account?->bank_branch . ')' : '';
                                $bank = $cashBankAccount?->account?->bank ? '-' . $cashBankAccount?->account?->bank->name . $bankBranch : '';
                                $method = $cashBankAccount?->paymentMethod ? $cashBankAccount?->paymentMethod->name : '';
                            }
                        @endphp

                        <tr>
                            <td class="text-start">{{ $voucherType }}</td>
                            <td class="text-start">{{ date($generalSettings['business_or_shop__date_format'], strtotime($date)) }}</td>
                            <td class="text-start">{{ $reference->voucherDescription->accountingVoucher->voucher_no }}</td>
                            <td class="text-start">{{ $method }}</td>
                            {{-- <td class="fw-bold">{{ $cashBankAccount?->account?->name.$accountNo.$bank }}</td> --}}
                            <td class="text-start"><b>{{ $cashBankAccount?->account?->name . $accountNo }}</b></td>

                            <td class="fw-bold text-start">{{ App\Utils\Converter::format_in_bdt($reference?->amount) }}</td>
                            @php
                                $totalReceivedAmount += $reference?->amount ? $reference?->amount : 0;
                            @endphp

                            <td class="action_hideable text-start">
                                @if ($reference->voucherDescription)
                                    @if ($reference->voucherDescription->accountingVoucher->voucher_type == 1)
                                        <a href="#" id="extra_details_btn" class="btn-sm">{{ __('Details') }}</a>
                                    @else
                                        <a href="#" id="extra_details_btn" class="btn-sm">{{ __('Details') }}</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">{{ __('No Data Found') }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">{{ __('Total Recovered') }} : </th>
                    <th class="text-start">{{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
