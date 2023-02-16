@php
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode - {{ auth()->user()->branch ? auth()->user()->branch->name : $generalSettings['business__shop_name'] }} </title>
    <link rel="stylesheet" href="{{ asset('backend/asset/css/bootstrap.min.css') }}">

    <style>
        /* p {margin: 0px;padding: 0px;font-size: 7px;}
        p.sku {font-size: 7px;margin: 0px;padding: 0;font-weight: 700;margin-bottom: 1px;} */
        .company_name {margin: 0;}
        .company_name {font-size: 10px !important;font-weight: 400;margin: 0;padding: 0;color: #000}
        .barcode {margin-bottom: -2px;}

        @if ($br_setting->is_continuous == 1)

            .div {page-break-after: always;}

            @page {

                .print_area: {
                    height: 100%;
                    width: 100%;
                }

                /* size: auto; */
                /* size: {{ $br_setting->paper_width }}in {{ $br_setting->paper_height }}in; */
                size: 38mm 25mm;
                 /* margin: 5px 0px; */
                /* margin: 0mm 15mm 0mm 15mm; */
                margin-top: 0.3cm;
                margin-bottom: 28px;
            }
        @else 

            @page {
                /* size: auto; */
                /* size: {{ $br_setting->paper_width }}in {{ $br_setting->paper_height }}in; */
                size: {{ $br_setting->paper_width }}in {{ $br_setting->paper_width }}in;
                /* margin: 0mm 15mm 0mm 15mm; */
                margin-top: 100cm;
                margin-bottom: 0px;
            }
        @endif

        html {
            margin: 0px;
            /* this affects the margin on the html before sending to printer */
        }

        body {font-family: Verdana, Geneva, Tahoma, sans-serif;}

        .product_name {font-size: 10px;font-weight: 400;color: #000}
        .product_price {font-size: 10px;font-weight: 400;color: #000}
        .product_code {font-size: 10px;font-weight: 400;color: #000}
        th {padding: 0px;letter-spacing: 1px;}
    </style>
</head>
@php
    $limit = 50;
    $currentPublished = 0;
@endphp
<body>
    <div class="print_area">
        @if ($br_setting->is_continuous == 1)
            @php $index = 0; @endphp
            @foreach ($req->product_ids as $product)

                @php
                    $qty = isset($req->left_qty[$index]) ? $req->left_qty[$index] : 0;
                    $barcodeType = isset($req->barcode_type[$index]) ? isset($req->barcode_type[$index]) : 'code128';
                @endphp

                @for ($i = 0; $i < $qty; $i++)

                    <div class="row justify-content-center div justify-center">
                        <div class="barcode_area text-center" style="margin-bottom: {{ $br_setting->top_margin }}in;">
                            <div class="barcode">
                                <div class="company_name row">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="company_name">
                                                    @if (isset($req->is_business_name))
                                                        {{ auth()->user()->branch ? auth()->user()->branch->name : $generalSettings['business__shop_name'] }}
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                                <div class="row justify-content-center">
                                    <img style="width: 45mm; height:7mm;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                </div>

                                <div class="row justify-content-center">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product_code">
                                                    {{ $req->product_code[$index] }}
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="product_details_area row">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_name">
                                                @if (isset($req->is_product_name))
                                                    @php
                                                        $variant = isset($req->is_product_variant) ? '-' . Str::limit($req->product_variant[$index], 10, '') : '';
                                                    @endphp
                                                    {{ Str::limit($req->product_name[$index], 12, '') . $variant }}
                                                    {{ isset($req->is_supplier_prefix) ? ':'.$req->supplier_prefix[$index] : '' }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="product_price">
                                                @if (isset($req->is_price))
                                                    {{ $generalSettings['business__currency'] }}
                                                    {{ App\Utils\Converter::format_in_bdt($req->product_price[$index]) }}
                                                    {{ isset($req->is_tax) ? '+ ' . $req->product_tax[$index] . '% VAT' : '' }}
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

                    @php $qty = isset($req->left_qty[$index]) ? $req->left_qty[$index] : 0; @endphp

                    @for ($i = 0; $i < $qty; $i++)
                        @php
                            $currentPublished += 1;
                        @endphp
                        <div class="barcode_area text-center" style="margin-bottom: {{$br_setting->top_margin}}in; margin-left : {{ $br_setting->left_margin }}in; height:{{ $br_setting->sticker_height }}in; width:{{ $br_setting->sticker_width }}in; ">
                            <div class="barcode">
                                <p class="company_name" style="margin: 0px;padding: 0px;font-size: 4px;">
                                    <strong>
                                        @if (isset($req->is_business_name))
                                            {{ auth()->user()->branch ? auth()->user()->branch->name : $generalSettings['business__shop_name'] }}
                                        @endif
                                    </strong>
                                </p>

                                <img style="width: 100%; height:25px; margin:auto;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                <p class="product_code" style="margin: 0px;padding: 0px;font-size: 7px;">{{ $req->product_code[$index] }}</p>
                            </div>

                            <div class="product_details_area">
                                @if (isset($req->is_product_name))
                                    <p class="pro_details" style="margin: 0px;padding: 0px;font-size: 10px;">
                                        @php
                                            $variant = isset($req->is_product_variant) ? (isset($req->product_variant[$index]) ? $req->product_variant[$index] : '' ) : '';
                                        @endphp
                                        {{ Str::limit($req->product_name[$index] . ' ' . $variant, 15, '.') }}
                                        : {{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                    </p>
                                @endif

                                @if (isset($req->is_price))
                                    <p class="price_details" style="margin: 0px;padding: 0px;font-size: 9px;">
                                        {{ $generalSettings['business__currency'] }}
                                        <b>{{ App\Utils\Converter::format_in_bdt($req->product_price[$index]) }}
                                        {{ isset($req->is_tax) ? '+ ' . App\Utils\Converter::format_in_bdt($req->product_tax[$index]) . '%' : '' }}</b>
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if ($currentPublished == $limit)
                            <div id="pageBreaker" style="page-break-after: always;"></div>
                            @php
                               $currentPublished = 0; 
                            @endphp
                        @endif
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
