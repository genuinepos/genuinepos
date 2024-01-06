@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = $generalSettings['business_or_shop__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
    $defaultLayout = DB::table('invoice_layouts')
        ->where('branch_id', null)
        ->where('is_default', 1)
        ->first();
    $invoiceLayout = $sale?->branch?->branchSetting?->addSaleInvoiceLayout ? $sale?->branch?->branchSetting?->addSaleInvoiceLayout : $defaultLayout;
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
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 0%;
        color: #CCC;
        background: #333;
        padding: 0;
        margin: 0;
    }
</style>

<div class="print_challan">
    <div class="details_area">
        @if ($invoiceLayout->is_header_less == 0)
            <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
                <div class="col-4">
                    @if ($sale->branch)

                        @if ($sale?->branch?->parent_branch_id)

                            @if ($sale->branch?->parentBranch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->parentBranch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->parentBranch?->name }}</span>
                            @endif
                        @else
                            @if ($sale->branch?->logo != 'default.png' && $invoiceLayout->show_shop_logo == 1)
                                <img style="height: 60px; width:200px;" src="{{ asset('uploads/branch_logo/' . $sale->branch?->logo) }}">
                            @else
                                <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $sale->branch?->name }}</span>
                            @endif
                        @endif
                    @else
                        @if ($generalSettings['business_or_shop__business_logo'] != null && $invoiceLayout->show_shop_logo == 1)
                            <img src="{{ asset('uploads/business_logo/' . $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                        @else
                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                        @endif
                    @endif
                </div>

                <div class="col-8 text-end">
                    <p style="text-transform: uppercase;" class="p-0 m-0">
                        <strong>
                            @if ($sale?->branch)
                                @if ($sale?->branch?->parent_branch_id)
                                    {{ $sale?->branch?->parentBranch?->name }}
                                @else
                                    {{ $sale?->branch?->name }}
                                @endif
                            @else
                                {{ $generalSettings['business_or_shop__business_name'] }}
                            @endif
                        </strong>
                    </p>

                    <p>
                        @if ($sale?->branch)
                            {{ $invoiceLayout->branch_city == 1 ? $sale->branch->city . ', ' : '' }}
                            {{ $invoiceLayout->branch_state == 1 ? $sale->branch->state . ', ' : '' }}
                            {{ $invoiceLayout->branch_zipcode == 1 ? $sale->branch->zip_code . ', ' : '' }}
                            {{ $invoiceLayout->branch_country == 1 ? $sale->branch->country : '' }}
                        @else
                            {{ $generalSettings['business_or_shop__address'] }}
                        @endif
                    </p>

                    <p>
                        @php
                            $email = $sale?->branch?->email ? $sale?->branch?->email : $generalSettings['business_or_shop__email'];
                            $phone = $sale?->branch?->phone ? $sale?->branch?->phone : $generalSettings['business_or_shop__phone'];
                        @endphp

                        @if ($invoiceLayout->branch_email)
                            <strong>{{ __('Email') }} : </strong> {{ $email }},
                        @endif

                        @if ($invoiceLayout->branch_phone)
                            <strong>{{ __('Phone') }} : </strong> {{ $phone }}
                        @endif
                    </p>
                </div>
            </div>
        @endif

        @if ($invoiceLayout->is_header_less == 0)
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <h5 style="text-transform: uppercase;"><strong>{{ $invoiceLayout->challan_heading }}</strong></h5>
                </div>
            </div>
        @endif

        @if ($invoiceLayout->is_header_less == 1)
            @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                <br />
            @endfor
        @endif

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    @if ($invoiceLayout->customer_name)
                        <li style="font-size:11px!important;"><strong>{{ __('Customer') }} : </strong>
                            {{ $sale?->customer?->name }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_address)
                        <li style="font-size:11px!important;"><strong>{{ __('Address') }} : </strong>
                            {{ $sale?->customer?->address }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_tax_no)
                        <li style="font-size:11px!important;"><strong>{{ __('Tax Number') }} : </strong>
                            {{ $sale?->customer?->tax_number }}
                        </li>
                    @endif

                    @if ($invoiceLayout->customer_phone)
                        <li style="font-size:11px!important;"><strong>{{ __('Phone') }} : </strong> {{ $sale?->customer?->phone }}</li>
                    @endif
                </ul>
            </div>

            <div class="col-lg-4 text-center">
                @if ($invoiceLayout->is_header_less == 1)
                    <div class="middle_header_text text-center">
                        <h5 style="text-transform: uppercase;">{{ $invoiceLayout->challan_heading }}</h5>
                    </div>
                @endif

                <img style="width: 170px; height:35px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($sale->invoice_id, $generator::TYPE_CODE_128)) }}">
            </div>

            <div class="col-lg-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;">
                        <strong>{{ __('Challan No') }} : </strong> {{ $sale->invoice_id }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Date') }} : </strong> {{ date($generalSettings['business_or_shop__date_format'], strtotime($sale->date)) . ' ' . $sale->time }}
                    </li>

                    <li style="font-size:11px!important;">
                        <strong>{{ __('Created By') }} : </strong> {{ $sale?->createdBy?->prefix . ' ' . $sale?->createdBy?->name . ' ' . $sale?->createdBy?->last_name }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table print-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('S/L') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Description') }}</th>
                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                        <th class="fw-bold text-end" style="font-size:11px!important;">{{ __('Unit') }}</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $saleProduct)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $saleProduct->p_name }}

                                @if ($saleProduct->variant_id)
                                    -{{ $saleProduct->variant_name }}
                                @endif
                                {!! $invoiceLayout->product_imei == 1 ? '<br><small class="text-muted">' . $saleProduct->description . '</small>' : '' !!}
                            </td>

                            <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->quantity }}</td>
                            <td class="text-end" style="font-size:11px!important;">{{ $saleProduct->unit_code_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (count($customerCopySaleProducts) > 15)
            <br>
            <div class="row page_break">
                <div class="col-12 text-end">
                    <h6><em>{{ __('Continued To This Next Page') }}....</em></h6>
                </div>
            </div>

            @if ($invoiceLayout->is_header_less == 1)
                @for ($i = 0; $i < $invoiceLayout->gap_from_top; $i++)
                    <br />
                @endfor
            @endif
        @endif

        <br><br>

        <div class="row">
            <div class="col-4">
                <div class="details_area text-start">
                    <p class="text-uppercase borderTop"><strong>{{ __("Customer's Signature") }}</strong></p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __('Prepared By') }}</strong></p>
                </div>
            </div>

            <div class="col-4">
                <div class="details_area text-end">
                    <p class="text-uppercase borderTop"><strong>{{ __('Authorized By') }}</strong></p>
                </div>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __('Print Date') }} : {{ date($generalSettings['business_or_shop__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (env('PRINT_SD_SALE') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ __('Powered By') }} <strong>@lang('SpeedDigit Software Solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __('Print Time') }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
