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
        @for ($i = 0; $i < 200; $i++)
            <div class="row justify-content-center">
                <div class="barcode_area text-center" style="width:3in;">
                    <div class="company_name">
                        <small class="p-0 m-0"><strong>SpeedDigit Pvt. Ltd.</strong></small>
                    </div>
                    <div class="barcode">
                        <div class="text-center">
                            <img style="width: 170px; height:35px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode('SDC154789', $generator::TYPE_CODE_128)) }}">
                        </div>
                        <div class="row">
                            <p class="sku">SDC154789</p>
                        </div>
                    </div>
                    <div class="product_details_area">
                        <p class="pro_details">{{Str::limit('Max Green MGO-PX1k 1KVA Standard Backup', 40)}}.  SP1257</p>
                        <p class="price_details">Price : $ 22000.00 + 5.00% Tax</p>
                    </div>
                </div>
            </div>
        @endfor
    </div>
    <button class="btn btn-success" onclick="window.print()">Print</button>
</body>
 <!--Jquery Cdn-->
 <script src="{{asset('public/backend/asset/cdn/js/jquery-3.6.0.js')}}"></script>
 <!--Jquery Cdn End-->
 <script>
     
 </script>
 <style>
    .product_details_area p {font-size: 8px;margin-top: -2px;}
    .product_details_area .price_details {margin-top: -15px;}
    p.sku {font-size: 9px;margin: 0px;padding: 0;font-weight: 700;margin-bottom: 1px;}
 </style>
</html>