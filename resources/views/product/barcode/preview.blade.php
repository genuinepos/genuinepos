@php
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Barcode</title>
    <link rel="stylesheet" href="{{ asset('public/backend/asset/css/bootstrap.min.css') }}">

    <style>
        p {
            margin: 0px;
            padding: 0px;
            font-size: 7px;
        }

        p.sku {
            font-size: 7px;
            margin: 0px;
            padding: 0;
            font-weight: 700;
            margin-bottom: 1px;
        }

        .company_name {
            margin-bottom: 0px;
        }

        .div {
            page-break-after: always;
        }

        .company_name small {
            font-size: 8px !important;
        }

        .barcode {
            margin-bottom: -2px;
        }

        @page {
            /* size: auto; */
            .print_area: {
                height: 100%;
                width: 100%;
            }
            size: {{ $br_setting->paper_width }}in {{ $br_setting->paper_height }}in;
            margin: 0mm;
            /* margin: 0mm 15mm 0mm 15mm; */

            .company_name {
                margin-top: 80px !important;
            }

        }


        html {
            /* background-color: #FFFFFF; */
            margin: 0px;
            /* this affects the margin on the html before sending to printer */
        }

        body {
            /* border: solid 1px blue; */
            margin: 0mm 15mm 0mm 15mm;
            /* margin you want for the content */
        }
        .justify-center {
            /* display: flex;
            justify-self: center;
            align-items: center; */
        }

    </style>


</head>

<body>
    <div class="print_area">
        @if ($br_setting->is_continuous == 1)
            @php $index = 0; @endphp
            @foreach ($req->product_ids as $product)

                @php
                    $qty = $req->left_qty[$index] ? (int) $req->left_qty[$index] : 0;
                    $barcodeType = $req->barcode_type[$index];
                @endphp
                @for ($i = 0; $i < $qty; $i++)
                    <div class="row justify-content-center div justify-center">
                        <div class="barcode_area text-center" style="margin-bottom: {{ $br_setting->top_margin }}in;">
                            <div class="barcode">
                                <br>
                                <br>
                                <div class="company_name row">
                                    <small class="p-0 m-0">
                                        <strong>
                                            @if (isset($req->is_business_name))
                                                {{ auth()->user()->branch ? auth()->user()->branch->name : json_decode($generalSettings->business, true)['shop_name'] }}
                                            @endif
                                        </strong>
                                    </small>
                                </div>
                                <div class="row justify-content-center">
                                    <img style="width: {{ $br_setting->sticker_width }}in; height:{{ $br_setting->sticker_height }}in;"
                                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                </div>
                                <div class="row justify-content-center">
                                    <p class="sku">{{ $req->product_code[$index] }}</p>
                                </div>
                            </div>
                            <div class="product_details_area row">
                                @if (isset($req->is_product_name))
                                    <p class="pro_details">
                                        @php
                                            $variant = isset($req->is_product_variant) ? $req->product_variant[$index] : '';
                                        @endphp
                                        {{ Str::limit($req->product_name[$index] . ' ' . $variant, 40) }}
                                        :{{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                    </p>
                                @endif

                                @if (isset($req->is_price))
                                    <p class="price_details">
                                        <b>Price :</b>
                                        {{ json_decode($generalSettings->business, true)['currency'] }}
                                        {{ bcadd($req->product_price[$index], 0, 2) }}
                                        {{ isset($req->is_tax) ? '+ ' . bcadd($req->product_tax[$index], 0, 2) . '% Tax' : '' }}
                                        {!! $req->packing_date[$index] ? '<br><b>Packing Date :</b> ' . date(json_decode($generalSettings->business, true)['date_format'], strtotime($req->packing_date[$index])) : '' !!}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endfor
                @php $index++; @endphp
            @endforeach
        @else
            <div class="row justify-content-center">
                @php $index = 0; @endphp
                @foreach ($req->product_ids as $product)
                    @php $qty = $req->left_qty[$index] ? (int)$req->left_qty[$index] : 0 @endphp
                    @for ($i = 0; $i < $qty; $i++)
                        <div class="barcode_area text-center"
                            style="width: {{ $br_setting->sticker_width }}in;margin-bottom: {{ $br_setting->top_margin }}in;margin-left:{{ $br_setting->left_margin }}in;">
                            <div class="barcode">
                                <div class="company_name row">
                                    <small class="p-0 m-0">
                                        <strong>
                                            @if (isset($req->is_business_name))
                                                {{ auth()->user()->branch ? auth()->user()->branch->name : json_decode($generalSettings->business, true)['shop_name'] }}
                                            @endif
                                        </strong>
                                    </small>
                                </div>
                                <div class="row justify-content-center">
                                    <img style="height:{{ $br_setting->sticker_height }}in;"
                                        src="data:image/png;base64,{{ base64_encode($generator->getBarcode($req->product_code[$index], $generator::TYPE_CODE_128)) }}">
                                </div>
                                <div class="row justify-content-center">
                                    <p class="sku">{{ $req->product_code[$index] }}</p>
                                </div>
                            </div>
                            <div class="product_details_area row">
                                @if (isset($req->is_product_name))
                                    <p class="pro_details">
                                        @php
                                            $variant = isset($req->is_product_variant) ? $req->product_variant[$index] : '';
                                        @endphp
                                        {{ Str::limit($req->product_name[$index] . ' ' . $variant, 40) }}
                                        :{{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                    </p>
                                @endif

                                @if (isset($req->is_price))
                                    <p class="price_details">
                                        <b>Price :
                                            {{ json_decode($generalSettings->business, true)['currency'] }}</b>
                                        {{ bcadd($req->product_price[$index], 0, 2) }}
                                        {{ isset($req->is_tax) ? '+ ' . bcadd($req->product_tax[$index], 0, 2) . '% Tax' : '' }}
                                        {!! $req->packing_date[$index] ? '<b>Packing Date :</b> ' . $req->packing_date[$index] : '' !!}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endfor
                    @php $index++; @endphp
                @endforeach
            </div>
        @endif
    </div>
    <button class="btn btn-success" onclick="window.print()">Print</button>
</body>
<!--Jquery Cdn-->
<script src="{{ asset('public/backend/asset/cdn/js/jquery-3.6.0.js') }}"></script>
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
