@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<div class="sale_payment_print_area">
    <div class="header_area">
        <div class="company_name text-center">
            <h3>
                <b>
                    @if ($payment->expense->branch)
                        {{ $payment->expense->branch->name . '/' . $payment->expense->branch->branch_code }}
                    @else
                        {{ json_decode($generalSettings->business, true)['shop_name'] }} (<b>Head Office</b>)
                    @endif
                </b>
            </h3>
            <p>
                @if ($payment->expense->branch)
                    {{ $payment->expense->branch->city . ', ' . $payment->expense->branch->state . ', ' . $payment->expense->branch->zip_code . ', ' . $payment->expense->branch->country }}
                @else
                    {{ json_decode($generalSettings->business, true)['address'] }}
                @endif
            </p>
            <h6><b>Expense Voucher</b></h6>
        </div>
    </div>

    <div class="reference_area pt-3">
        <div class="row">
            <div class="col-md-6">
                <p><b>Reference No :</b> {{ $payment->expense->invoice_id }}</p>
                <p><b>Voucher No :</b> {{ $payment->invoice_id }}</p>
            </div>

            <div class="col-md-6">
                <p><b>Date :</b> {{ date('d/m/Y', strtotime($payment->date))  }}</p>
            </div>
        </div>
    </div>

    <div class="total_amount_table_area pt-5">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-sm table-md">
                    <tbody>
                        <tr>
                            <th class="text-start">Expense Category:</th>
                            <td class="text-start">{{ $payment->expense->expanse_category->name }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Expense For:</th>
                            <td class="text-start">{{ $payment->expense->admin ? $payment->expense->admin->prefix.' '.$payment->expense->admin->name.' '.$payment->expense->admin->last_name : 'N/A' }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Description:</th>
                            <td class="text-start"><small>{{ $payment->expense->note }}</small> </td>
                        </tr>

                        <tr>
                            <th class="text-start">Paid:</th>
                            <td class="text-start">
                               <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $payment->paid_amount }}</b> 
                            </td>
                        </tr>

                        <tr>
                            <th class="text-start">Method :</th>
                            <td class="text-start">{{ $payment->pay_mode }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">In Word :</th>
                            <td class="text-start"><span id="inword"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- <div class="col-md-6">
                <table class="table table-sm">
                    <tbody>

                        <tr>
                            <th width="50%" class="text-start">Paid On :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ $payment->date . ' ' . $payment->time }}
                            </td>
                        </tr>

                        <tr>
                            <th width="50%" class="text-start">Payment Note :</th>
                            <td width="50%" class="text-navy-blue">
                                {{ $payment->note }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> --}}
        </div>
    </div>

    <div class="signature_area pt-5 mt-5 d-none">
        <table class="w-100 mt-5">
            <tbody>
                <tr>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Receiver</p> </th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Made By</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Account Manger</p></th>
                    <th class="text-center"><p style="width: 70%; border-top:1px solid black;">Authority</p></th>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        <img style="width: 170px; height:40px;" class="mt-3" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($payment->invoice_id, $generator::TYPE_CODE_128)) }}">
                    </td>
                </tr>

                <tr class="text-center">
                    <td colspan="4" class="text-center">
                        {{ $payment->invoice_id }}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="text-navy-blue text-center"><small>Software by <b>SpeedDigit Pvt. Ltd.</b></small> </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
        'nineteen'
    ];

    function convert_millions(num) {
        if (num >= 100000) {
            return convert_millions(Math.floor(num / 100000)) + " Lack " + convert_thousands(num % 1000000);
        } else {
            return convert_thousands(num);
        }
    }

    function convert_thousands(num) {
        if (num >= 1000) {
            return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
        } else {
            return convert_hundreds(num);
        }
    }

    function convert_hundreds(num) {
        if (num > 99) {
            return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
        } else {
            return convert_tens(num);
        }
    }

    function convert_tens(num) {
        if (num < 10) return ones[num];
        else if (num >= 10 && num < 20) return teens[num - 10];
        else {
            return tens[Math.floor(num / 10)] + " " + ones[num % 10];
        }
    }

    function convert(num) {
        if (num == 0) return "zero";
        else return convert_millions(num);
    }

    document.getElementById('inword').innerHTML = convert(parseInt("{{ $payment->paid_amount }}")).replace(
        'undefined', '(some Penny)').toUpperCase() + ' ONLY.';
</script>