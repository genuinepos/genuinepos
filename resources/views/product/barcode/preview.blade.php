@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Barcode') }} -
        @if (auth()->user()?->branch)
            @if (auth()->user()?->branch?->parentBranch)
                {{ auth()->user()?->branch?->parentBranch?->name }}
            @else
                {{ auth()->user()?->branch?->name }}
            @endif
        @else
            {{ $generalSettings['business_or_shop__business_name'] }}
        @endif
    </title>
    <link rel="stylesheet" href="{{ asset('backend/asset/css/bootstrap.min.css') }}">

    <style>
        /* p {margin: 0px;padding: 0px;font-size: 7px;}
        p.sku {font-size: 7px;margin: 0px;padding: 0;font-weight: 700;margin-bottom: 1px;} */

        body {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-family: "Poppins", sans-serif;
        }

        /* .barcode {
            margin-bottom: -2px;
        } */

        @if ($barcodeSetting->is_continuous == 1)
            /* .div {
                page-break-after: always;
            } */

            th {
                font-weight: 400;
                padding: 0;
                letter-spacing: 1px;
            }

            .company_name {
                font-size: {{ $barcodeSetting->company_name_size }}px !important;
                font-weight: {{ $barcodeSetting->company_name_bold_or_regular == 1 ? 'bold' : '400' }};
                margin: 0;
                padding: 0;
                color: #000;
            }

            .barcode_area {
                margin-top: {{ $barcodeSetting->top_margin }}px !important;
            }

            .product_name {
                font-size: {{ $barcodeSetting->product_name_size }}px !important;
                font-weight: {{ $barcodeSetting->product_name_bold_or_regular == 1 ? 'bold' : '400' }};
                color: #000;
            }

            .product_price {
                font-size: {{ $barcodeSetting->price_size }}px !important;
                font-weight: {{ $barcodeSetting->price_bold_or_regular == 1 ? 'bold' : '400' }} !important;
                color: #000;
            }

            .product_code {
                font-size: {{ $barcodeSetting->product_code_size }}px !important;
                color: #000;
                letter-spacing: 3px;
            }

            @media print {
                th {
                    font-weight: 400;
                    padding: 0;
                    letter-spacing: 1px;
                }

                .company_name {
                    font-size: {{ $barcodeSetting->company_name_size }}px !important;
                    font-weight: {{ $barcodeSetting->company_name_bold_or_regular == 1 ? 'bold' : '400' }};
                    margin: 0;
                    padding: 0;
                    color: #000;
                }

                .barcode_area {
                    margin-top: {{ $barcodeSetting->top_margin }}px !important;
                }

                .product_name {
                    font-size: {{ $barcodeSetting->product_name_size }}px !important;
                    font-weight: {{ $barcodeSetting->product_name_bold_or_regular == 1 ? 'bold' : '400' }};
                    color: #000;
                }

                .product_price {
                    font-size: {{ $barcodeSetting->price_size }}px !important;
                    font-weight: {{ $barcodeSetting->price_bold_or_regular == 1 ? 'bold' : '400' }} !important;
                    color: #000;
                }

                .product_code {
                    font-size: {{ $barcodeSetting->product_code_size }}px !important;
                    color: #000;
                    letter-spacing: 3px;
                }

                @page {
                    /* size: auto; */
                    size: {{ $barcodeSetting->paper_width }}mm {{ $barcodeSetting->paper_height }}mm portrait landscape !important;
                    /* size: 1.4in 0.90in;  */
                    /* size: 1.1in 0.80in; */
                    /* margin: 5px 0px; */
                    /* margin: 0mm 15mm 0mm 15mm; */
                    /* margin-top: 0.3cm; */
                    margin: 0 auto;
                    /* margin-bottom: 5px; */
                    margin-left: {{ $barcodeSetting->left_margin }}px !important;
                    margin-right: {{ $barcodeSetting->right_margin }}px !important;
                }

            }
        @else
            .print_area: {
                height: 100%;
                width: 100%;
            }

            .barcode_area {
                margin-top: {{ $barcodeSetting->top_margin }}px !important;
            }

            .company_name {
                font-size: {{ $barcodeSetting->company_name_size }}px !important;
                font-weight: {{ $barcodeSetting->company_name_bold_or_regular == 1 ? 'bold' : '400' }};
                margin: 0;
                padding: 0;
                color: #000;
            }

            .barcode_area {
                margin-top: {{ $barcodeSetting->top_margin }}px !important;
            }

            .product_name {
                font-size: {{ $barcodeSetting->product_name_size }}px !important;
                font-weight: {{ $barcodeSetting->product_name_bold_or_regular == 1 ? 'bold' : '400' }};
                color: #000;
            }

            .product_price {
                font-size: {{ $barcodeSetting->price_size }}px !important;
                font-weight: {{ $barcodeSetting->price_bold_or_regular == 1 ? 'bold' : '400' }} !important;
                color: #000;
            }

            .product_code {
                font-size: {{ $barcodeSetting->product_code_size }}px !important;
                color: #000;
                letter-spacing: 3px;
            }

            @page {
                /* size: auto; */
                /* size: {{ $barcodeSetting->paper_width }}in {{ $barcodeSetting->paper_height }}in; */
                size: a4 portrait landscape;
                /* margin: 0mm 15mm 0mm 15mm; */
                margin-top: 100cm;
                margin-bottom: 0px;
            }
        @endif

        html {
            margin: 0px;
            /* this affects the margin on the html before sending to printer */
        }
    </style>
</head>
@php
    $limit = 50;
    $currentPublished = 0;
@endphp

<body>
    <div class="print_area">
        @if ($barcodeSetting->is_continuous == 1)
            @php
                $index = 0;

                $barWidth = ($barcodeSetting->paper_width / 100) * $barcodeSetting->bar_width;
                $barHeight = ($barcodeSetting->paper_height / 100) * $barcodeSetting->bar_height;
            @endphp
            @foreach ($req->product_ids as $product)
                @php
                    $qty = isset($req->left_quantities[$index]) ? $req->left_quantities[$index] : 0;
                @endphp

                @for ($i = 0; $i < $qty; $i++)
                    <div class="row justify-content-center div justify-center" style="font-family: Arial, Helvetica, sans-serif;overflow: hidden;page-break-after: always; page-break-inside: avoid;">
                        {{-- <div class="barcode_area text-center" style="margin-bottom: {{ $barcodeSetting->top_margin }}in;"> --}}
                        <div class="barcode_area text-center">
                            <div class="barcode">
                                <div class="row justify-content-center p-0 m-0">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="company_name">
                                                    @if (isset($req->is_business_name))
                                                        @if (auth()->user()?->branch)
                                                            @if (auth()->user()?->branch?->parentBranch)
                                                                {{ auth()->user()?->branch?->parentBranch?->name }}
                                                            @else
                                                                {{ auth()->user()?->branch?->name }}
                                                            @endif
                                                        @else
                                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                                        @endif
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="row justify-content-center">
                                    {{-- style="width: 45mm; height:7mm;"  --}}
                                    {{-- <img style="width: 45mm; height:7mm;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_codes[$index], $generator::TYPE_CODE_128)) }}"> --}}

                                    {{-- <img style="width: {{ $barcodeSetting->bar_width }}%!important; height:{{ $barcodeSetting->bar_width }}$!important;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_codes[$index], $generator::TYPE_CODE_128)) }}"> --}}

                                    <img style="width: {{ $barWidth }}mm!important; height:{{ $barHeight }}mm!important;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_codes[$index], $generator::TYPE_CODE_128)) }}">
                                    {{-- <p>{{ $barWidth }}</p> --}}
                                </div>

                                <div class="row justify-content-center">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_code">
                                                    {{ $req->product_codes[$index] }}
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="product_details_area row m-0 p-0">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_name">
                                                @if (isset($req->is_product_name))
                                                    @php
                                                        $variant = isset($req->is_product_variant) ? (isset($req->variant_names[$index]) ? '-' . $req->variant_names[$index] : '') : '';
                                                    @endphp
                                                    {{ Str::limit($req->product_names[$index], 30, '') . $variant }}
                                                    @php
                                                        $supplierPrefix = isset($req->supplier_prefixes[$index]) ? ' (' . $req->supplier_prefixes[$index] . ')' : '';
                                                    @endphp
                                                    {{ isset($req->is_supplier_prefix) ? $supplierPrefix : '' }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="product_price">
                                                @if (isset($req->is_price))
                                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                                    {{ App\Utils\Converter::format_in_bdt($req->prices_inc_tax[$index]) }}
                                                    {{-- {{ isset($req->is_tax) ? '+ ' . $req->tax_percents[$index] . '%' : '' }} --}}
                                                    {{ isset($req->is_tax) ? '+ VAT' : '' }}
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                @endfor
                @php $index++; @endphp
            @endforeach
        @else
            <div class="row">
                @php $index = 0; @endphp
                @foreach ($req->product_ids as $product)
                    @php $qty = isset($req->left_quantities[$index]) ? $req->left_quantities[$index] : 0; @endphp

                    @for ($i = 0; $i < $qty; $i++)
                        @php
                            $currentPublished += 1;
                        @endphp
                        <div class="barcode_area text-center" style=" margin-bottom: {{ $barcodeSetting->top_margin }}in; margin-left : {{ $barcodeSetting->left_margin }}in; height:auto; width:{{ $barcodeSetting->bar_width }}%;page-break-inside: avoid;">
                            <div class="barcode">
                                <p class="company_name" style="margin: 0px;padding: 0px;">
                                    @if (isset($req->is_business_name))
                                        @if (auth()->user()?->branch)
                                            @if (auth()->user()?->branch?->parentBranch)
                                                {{ auth()->user()?->branch?->parentBranch?->name }}
                                            @else
                                                {{ auth()->user()?->branch?->name }}
                                            @endif
                                        @else
                                            {{ $generalSettings['business_or_shop__business_name'] }}
                                        @endif
                                    @endif
                                </p>

                                <img style="width: 100%; height:22px; margin:auto;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_codes[$index], $generator::TYPE_CODE_128)) }}">
                                <p class="product_code" style="margin: 0px!important;padding: 0px!important;font-size: 7px;">{{ $req->product_codes[$index] }}</p>
                            </div>

                            <div class="product_details_area" style="margin-top: -2px;">
                                @if (isset($req->is_product_name))
                                    <p class="product_name" style="margin: 0px;padding: 0px;font-size: 8px;line-height:1.3!important;">
                                        @php
                                            $variant = isset($req->is_product_variant) ? (isset($req->variant_names[$index]) ? '-' . $req->variant_names[$index] : '') : '';
                                        @endphp
                                        {{ Str::limit($req->product_names[$index], 50, '.') . $variant }}
                                        @php
                                            $supplierPrefix = isset($req->supplier_prefixes[$index]) ? ' (' . $req->supplier_prefixes[$index] . ')' : '';
                                        @endphp
                                        {{ isset($req->is_supplier_prefix) ? $supplierPrefix : '' }}
                                    </p>
                                @endif

                                @if (isset($req->is_price))
                                    <p class="product_price fw-bold" style="margin: 0px;padding: 0px;font-size: 8px;line-height:1.5!important;">
                                        {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                        {{ App\Utils\Converter::format_in_bdt($req->prices_inc_tax[$index]) }}
                                        {{-- {{ isset($req->is_tax) ? '+ ' . $req->tax_percents[$index] . '%' : '' }} --}}
                                        {{ isset($req->is_tax) ? '+ VAT' : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- @if ($currentPublished == $limit)
                            <div id="pageBreaker" style="page-break-after: always;"></div>
                            @php
                                $currentPublished = 0;
                            @endphp
                        @endif --}}
                    @endfor
                    @php $index++; @endphp
                @endforeach
            </div>
        @endif
    </div>

    {{-- <button class="btn btn-success" onclick="window.print()">@lang('menu.print')</button> --}}
</body>
<!--Jquery Cdn-->
<script src="{{ asset('backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
<!--Jquery Cdn End-->
<script>
    function auto_print() {

        window.print();
    }

    setTimeout(function() {
        auto_print();
    }, 300);
</script>

</html>
