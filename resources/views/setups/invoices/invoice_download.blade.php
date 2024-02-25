<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $rtl  = app()->isLocale('ar');
@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title') {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('backend/asset/css/comon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/asset/css/theme.css') }}">

</head>
<body id="dashboard-8" class="{{ $generalSettings['system__theme_color'] ?? 'dark-theme' }}
@if($rtl) rtl @endif" @if($rtl) dir="rtl" @endif>
<div class="all__content">
    <div class="main-woaper px-2">
        <div class="row g-4 justify-content-center">
            <div class="col-12">
                <div class="panel rounded-0">
                    <div class="panel-body invoice" id="invoiceBody">
                        <div class="invoice-header mb-25">
                            <div class="row justify-content-between align-items-end">
                                <div class="col-xl-4 col-lg-5 col-sm-6">
                                    <div class="shop-address">
                                        <div class="logo mb-20">
                                            <img src="{{ asset('logo.png') }}" width="40" alt="Logo">
                                        </div>
                                        <div class="part-txt">
                                            <p class="mb-1">House: 17, Road 13/A, Sector 06, Uttara, Dhaka-1230</p>
                                            <p class="mb-1">Email: info@gposs.com</p>
                                            <p class="mb-1">Phone: 01792288555</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex gap-xl-4 gap-3 status-row">
                                        <div class="w-50">
                                            <div class="payment-status">
                                                <label class="form-label">Payment Status:</label>
                                                @if($transaction->payment_status)
                                                    <span class="text-success">Paid</span>
                                                @else
                                                    <span class="text-danger">Unpaid</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-body">
                            <div class="info-card-wrap mb-25">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="info-card">
                                            <h3>Customer Details:</h3>
                                            <ul class="p-0">
                                                <li><span>Name:</span> {{ optional($transaction->subscription)->user->name }}</li>
                                                <li><span>Email:</span> {{ optional($transaction->subscription)->user->email }}</li>
                                                <li><span>Phone:</span> {{ optional($transaction->subscription)->user->phone }}</li>
                                                <li><span>Address:</span> {{ $generalSettings['business_or_shop__address'] ?? '' }}, Dhaka - 1202, Bangladesh</li>
                                            </ul>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4 col-sm-6">
                                        <div class="info-card">
                                            <h3>Shipping Address:</h3>
                                            <ul class="p-0">
                                                <li><span>Name:</span> Shaikh Abu Dardah</li>
                                                <li><span>Email:</span> iamdarda999@gmail.com</li>
                                                <li><span>Phone:</span> +880 1234 567890</li>
                                                <li><span>Address:</span> 90 Tejkunipara, Dhaka - 1202, Bangladesh</li>
                                            </ul>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6 col-sm-6 float-right">
                                        <div class="info-card">
                                            <h3>Invoice Details:</h3>
                                            <ul class="p-0">
                                                <li><span>Invoice No.:</span> 22123101</li>
                                                <li><span>Invoice Date:</span> {{ $transaction->payment_date->format('d-m-Y') }}</li>
                                                <li><span>Total Amount:</span> ${{ $transaction->total_payable_amount }}</li>
                                                <li><span>Payment Method:</span> ${{ $transaction->payment_method_name }}</li>
                                                <li><span>Payment Status:</span>
                                                    @if($transaction->payment_status)
                                                    <span class="text-success">Paid</span>
                                                    @else
                                                    <span class="text-danger">Unpaid</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mb-25">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Package</th>
                                            <th>Qty.</th>
                                            <th>Price</th>
                                            <th>Tax</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01</td>
                                            <td>{{ optional($transaction->plan)->name }}</td>
                                            <td>01</td>
                                            <td>${{ optional($transaction->plan)->price_per_year }}</td>
                                            <td>$00</td>
                                            <td>${{ optional($transaction->plan)->price_per_year }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="total-payment-area row justify-content-end mb-25">
                                <div class="col-md-4 col-sm-6">
                                    <ul>
                                        <li class="d-flex justify-content-between">Net Total:<span>${{ $transaction->total_payable_amount }}</span></li>
                                        <li class="d-flex justify-content-between">Vat:<span>$0</span></li>
                                        <li class="d-flex justify-content-between">Total:<span>${{ $transaction->total_payable_amount }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="invoice-note text-center mb-0">N.B: Should you encounter any problems, do not hesitate to reach out to us.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
