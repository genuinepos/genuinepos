<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Sale  Information:
    <p>Product: {{ $sale->products->first()->name }}</p>
</body>
</html>
