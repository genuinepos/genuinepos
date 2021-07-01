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
</head>
<body>
    <div class="print_area">
        
        @if ($br_setting->is_continuous == 1)
            @php $index = 0; @endphp
            <div class="row justify-content-center div">
            @foreach ($req->product_ids as $product)
                @php $qty = $req->left_qty[$index] ? (int)$req->left_qty[$index] : 0 @endphp
                @for ($i = 0; $i < $qty; $i++)
                    <div class="barcode_area text-center" style="margin-top: {{ $br_setting->top_margin }}in;">
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
                                <img style="width: {{ $br_setting->sticker_width }}in; height:{{ $br_setting->sticker_height }}in;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode('SDC154789', $generator::TYPE_CODE_128)) }}">
                            </div>
                            <div class="row justify-content-center">
                                <p class="sku">SDC154789</p>
                            </div>
                        </div>
                        <div class="product_details_area row">
                            @if (isset($req->is_product_name))
                                <p class="pro_details">
                                    {{  Str::limit($req->product_name[$index].' '.$req->product_variant[$index], 40) }}. 
                                    {{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                </p>
                            @endif

                            @if (isset($req->is_price))
                            <p class="price_details">
                                Price : {{json_decode($generalSettings->business, true)['currency']}} {{ bcadd($req->product_price[$index], 0, 2) }}  
                                {{isset($req->is_tax) ? '+ '. bcadd($req->product_tax[$index], 0, 2) .' Tax' : ''}}
                            </p>
                            @endif
                        </div>
                    </div>
                @endfor 
                @php $index++; @endphp
            @endforeach
        </div>
        @else
            @php $index = 0; @endphp
            @foreach ($req->product_ids as $product)
                @php $qty = $req->left_qty[$index] ? (int)$req->left_qty[$index] : 0 @endphp
                @for ($i = 0; $i < $qty; $i++)
                <div class="row justify-content-center">
                    <div class="barcode_area text-center" style="width:3in;margin-top: {{ $br_setting->top_margin }}in;">
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
                                <img style="width: {{ $br_setting->sticker_width }}in; height:{{ $br_setting->sticker_height }}in;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode('SDC154789', $generator::TYPE_CODE_128)) }}">
                            </div>
                            <div class="row justify-content-center">
                                <p class="sku">SDC154789</p>
                            </div>
                        </div>
                        <div class="product_details_area row">
                            @if (isset($req->is_product_name))
                                <p class="pro_details">
                                    {{  Str::limit($req->product_name[$index].' '.$req->product_variant[$index], 40) }}. 
                                    {{ isset($req->is_supplier_prefix) ? $req->supplier_prefix[$index] : '' }}
                                </p>
                            @endif

                            @if (isset($req->is_price))
                            <p class="price_details">
                                Price : {{json_decode($generalSettings->business, true)['currency']}} {{ bcadd($req->product_price[$index], 0, 2) }}  
                                {{isset($req->is_tax) ? '+ '. bcadd($req->product_tax[$index], 0, 2) .' Tax' : ''}}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endfor 
                @php $index++; @endphp
            @endforeach
        @endif
    </div>
    <button class="btn btn-success" onclick="window.print()">Print</button>
</body>
 <!--Jquery Cdn-->
 <script src="{{asset('public/backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
 <!--Jquery Cdn End-->
 <script>
     
 </script>
 <style>
     /* .product_details_area {margin-top: 14px;} */
    /* .product_details_area p {font-size: 8px;margin-top: -17px;} */
    p{margin: 0px;padding: 0px;font-size: 8px;}
    p.sku {font-size: 8px;margin: 0px;padding: 0;font-weight: 700;margin-bottom: 1px;}
    .company_name {margin-bottom: -2px;}
    .div{page-break-after: always;}
    @page {
        size:{{ $br_setting->paper_width }}in {{ $br_setting->paper_height }}in;
        margin:5px 5px 5px 1px;
    } 
	
 </style>
</html>