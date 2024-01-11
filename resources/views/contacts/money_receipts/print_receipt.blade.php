@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $dateFormat = $generalSettings['business_or_shop__date_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp

<style>
    @media print {
        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-footer-group
        }
    }

    @page {
        size: a4;
        margin-top: 0.8cm;
        margin-bottom: 35px;
        margin-left: 10px;
        margin-right: 10px;
    }

    div#footer {
        position: fixed;
        bottom: 25px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }
</style>
<!--Money Receipt design-->
<div class="money_receipt_print_area">
    <div class="print_content">
        @if ($moneyReceipt->is_header_less == 0)

            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($moneyReceipt->branch)
                        @if ($moneyReceipt->branch->logo != 'default.png')
                            <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $purchase->branch->logo) }}">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $purchase->branch->name }}</span>
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null)
                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;">
                        <strong>
                            @if ($moneyReceipt->branch)
                                {!! $moneyReceipt->branch->name !!}
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p>
                        @if ($moneyReceipt?->branch)
                            {{ $moneyReceipt->branch->city . ', ' . $moneyReceipt->branch->state . ', ' . $moneyReceipt->branch->zip_code . ', ' . $moneyReceipt->branch->country }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @if ($moneyReceipt?->branch)
                            <strong>@lang('menu.email') : </strong>{{ $moneyReceipt?->branch?->email }},
                            <strong>@lang('menu.phone') : </strong>{{ $moneyReceipt?->branch?->phone }}
                        @else
                            <strong>@lang('menu.email') : </strong>{{ $generalSettings['business_or_shop__email'] }},
                            <strong>@lang('menu.phone') : </strong>{{ $generalSettings['business_or_shop__phone'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h4 class="text-uppercase"><strong>{{ __('Money Receipt Voucher') }}</strong></h4>
                </div>
            </div>
        @endif

        @if ($moneyReceipt->is_header_less == 1)
            @for ($i = 0; $i < $moneyReceipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row">
            <div class="col-4 text-start">
                <p style="font-size:11px!important"><b>{{ __('Voucher No') }}</b> : {{ $moneyReceipt->voucher_no }}</p>
            </div>

            <div class="col-4 text-center">
                @if ($moneyReceipt->is_header_less == 1)
                    <h6 class="text-uppercase"><strong>{{ __('Monery Receipt Voucher') }}</strong></h6>
                @endif
            </div>

            <div class="col-4 text-end">
                <p style="font-size:11px!important"><b>{{ __('Date') }}</b> : {{ $moneyReceipt->is_date ? date($generalSettings['business_or_shop__date_format'], strtotime($moneyReceipt->date_ts)) : '.......................................' }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important"><b> {{ __('Received With Thanks From') }} </b> : {{ $moneyReceipt?->contact?->name }}</p>
                    </div>
                    <div class="col-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important"><b>{{ __('Amount Of Money') }}</b> : {{ $moneyReceipt->amount > 0 ? $generalSettings['business_or_shop__currency'] . ' ' . App\Utils\Converter::format_in_bdt($moneyReceipt->amount) : '' }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important"><b>{{ __('Inword') }}</b> :
                            @if ($moneyReceipt->amount > 0)
                                <span style="text-transform: uppercase;" id="inWord"></span>.
                            @endif
                        </p>
                    </div>
                    <div class="col-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important"><b>{{ __('Paid To') }}</b> : {{ $moneyReceipt->receiver }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <p style="font-size:11px!important"><b>{{ __('On Account Of') }}</b> : {{ $moneyReceipt->ac_details }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-12">
                <p style="font-size:11px!important"><b>{{ __('Pay Method') }} </b> : {{ __('Cash/Card/Bank-Transfer/Cheque/Advanced') }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-12 text-center">
                <p style="font-size:11px!important"><b>{{ $moneyReceipt->note }}</b></p>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-6">
                <div class="details_area">
                    <p class="borderTop text-uppercase"><strong>{{ __("Customer's Signature") }}</strong></p>
                </div>
            </div>
            <div class="col-6">
                <div class="details_area text-end">
                    <p class="borderTop text-uppercase"><strong>{{ __('Signature Of Authority') }}</strong></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($moneyReceipt->voucher_no, $generator::TYPE_CODE_128)) }}">
                <small class="d-block p-0 m-0" style="font-size: 9px!important;"><strong>{{ $moneyReceipt->voucher_no }}</strong></small>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (config('company.print_on_company'))
                        <small class="d-block" style="font-size: 9px!important;">@lang('menu.powered_by') <strong>@lang('menu.speedDigit_software_solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Money Receipt design end-->

<script>
    var a = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen '];
    var b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    function inWords(num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
        return str;
    }
    document.getElementById('inWord').innerHTML = inWords(parseInt("{{ $moneyReceipt->amount ? $moneyReceipt->amount : 0 }}"));
</script>
