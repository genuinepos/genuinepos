@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp 
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($loan->branch)
                        {{ $loan->branch->name . '/' . $loan->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                    @endif
                </b>
            </h3>
            
            <p>
                @if ($loan->branch)
                    {{ $loan->branch->city . ', ' . $loan->branch->branch->state . ', ' . $loan->branch->zip_code . ', ' . $loan->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <h6 style="margin-top: 10px;"><b>Loan Details</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <p><b>Title :</b>
        {{ $loan->type == 1 ? 'Loan pay' : 'Loan Receive' }} </p>
        <p><b>Company/People :</b> {{ $loan->company->name }}</p>
        <p><b>Address :</b></p>
        <p><b>Phone :</b></p>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">Voucher No :</th>
                            <td width="50%">
                                {{ $loan->reference_no }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Paid Amount' : 'Receive Amount' }}</th>
                            <td width="50%">
                                {{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ App\Utils\Converter::format_in_bdt($loan->loan_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Debit Account' : 'Credit Account' }}</th>
                            <td width="50%">{{ $loan->account ? $loan->account->name.' (A/C: '.$loan->account->account_number.')' : 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="50%" class="text-start">{{ $loan->type == 1 ? 'Receive Amount' : 'Paid Amount' }}</th>
                            <td width="50%">
                                <td width="50%">{{ $loan->type == 1 ? App\Utils\Converter::format_in_bdt($loan->total_receive) : App\Utils\Converter::format_in_bdt($loan->total_paid) }}</td>
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Due</th>
                            <td width="50%">
                                <td width="50%">{{ $loan->type == 1 ? App\Utils\Converter::format_in_bdt($loan->total_receive) : App\Utils\Converter::format_in_bdt($loan->due) }}</td>
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Loan Reason :</th>
                            <td width="50%">
                                {{ $loan->loan_reason }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-none">
        <br>
        <table class="w-100 pt-5">
            <tbody>
                <tr>
                    <th width="50%">Signature Of Receiver</th>
                    <th width="50%" class="text-end">Signature Of Provider</th>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <img style="width: 170px; height:20px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($loan->reference_no , $generator::TYPE_CODE_128)) }}">
                        <p>{{ $loan->reference_no }}</p>
                    </td>
                </tr>

                @if (env('PRINT_SD_PAYMENT') == true)
                    <tr>
                        <td colspan="2" class="text-center">Software by SpeedDigit Pvt. Ltd.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>