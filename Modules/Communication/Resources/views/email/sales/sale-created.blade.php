<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3>{{__('Sale  Information:')}}</h3>
    @foreach($sale->sale_products as  $product)
    <ul>
        <li>Prdoduct name: {{ $product->name }}</li>
        <li>Quantity: </li>
        <li>Price: </li>
    </ul>
    <hr>
    @endforeach

    <div>
        <p>Sub Total: </p>
        <p>Discount: </p>
        <hr>
        <p>Total: </p>
    </div>

</body>
</html>
