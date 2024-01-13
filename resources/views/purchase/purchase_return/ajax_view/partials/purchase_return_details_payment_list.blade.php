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
        <table class="table modal-table table-sm">
            <thead>
                <tr class="bg-primary text-white">
                    <th>{{ __('Voucher Type') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Voucher No') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Account') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th class="action_hideable">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody id="p_details_payment_list">
                @php
                    $totalReceivedAmount = 0;
                @endphp
                @if (count($return->references) > 0)

                    @foreach ($return->references as $reference)
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
                            <td class="fw-bold">{{ $voucherType }}</td>
                            <td class="fw-bold">{{ date($generalSettings['business_or_shop__date_format'], strtotime($date)) }}</td>
                            <td class="fw-bold">{{ $reference->voucherDescription->accountingVoucher->voucher_no }}</td>
                            <td><b>{{ $method }}</b></td>
                            {{-- <td class="fw-bold">{{ $cashBankAccount?->account?->name.$accountNo.$bank }}</td> --}}
                            <td><b>{{ $cashBankAccount?->account?->name . $accountNo }}</b></td>

                            <td class="fw-bold">{{ App\Utils\Converter::format_in_bdt($reference?->amount) }}</td>
                            @php
                                $totalReceivedAmount += $reference?->amount ? $reference?->amount : 0;
                            @endphp

                            <td class="action_hideable">
                                @if ($reference->voucherDescription)
                                    @if ($reference->voucherDescription->accountingVoucher->voucher_type == 1)
                                        <a href="#" id="extra_details_btn" class="btn btn-sm btn-info">{{ __('Details') }}</a>
                                    @else
                                        <a href="#" id="extra_details_btn" class="btn btn-sm btn-info">{{ __('Details') }}</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">{{ __('Data Not Found') }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">{{ __('Total Received') }} : </th>
                    <th>{{ App\Utils\Converter::format_in_bdt($totalReceivedAmount) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
