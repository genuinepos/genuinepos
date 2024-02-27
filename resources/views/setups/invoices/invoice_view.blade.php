@extends('layout.master')
@push('stylesheets')
    <style>
        .card-body {
            flex: 1 1 auto;
            padding: 0.4rem 0.4rem;
        }
        .invoice-table {
            margin-bottom: 0;
        }
        .invoice-table th {
            padding: 10px 20px;
            font-weight: 400;
        }
        .invoice-table th:last-child {
            text-align: right;
            }
        .invoice-table td {
            padding: 15.7px 20px;
            vertical-align: middle;
        }
        .invoice-table thead {
            background: rgba(255, 255, 255, 0.05);
        }
        .invoice-table tbody tr:last-child td {
            border-bottom: 0;
        }
        .invoice {
        padding: 30px;
        }
        .invoice .invoice-header .shop-address {
        font-family: "Lato", sans-serif;
        font-size: 16px;
        line-height: 1.3;
        letter-spacing: 0.5px;
        }
        .invoice .invoice-header .shop-address p {
        margin-bottom: 6px !important;
        }
        .invoice .invoice-header .shop-address p:last-child {
        margin-bottom: -5px !important;
        }
        .invoice .info-card h3 {
        font-size: 20px;
        font-weight: 500;
        line-height: 100%;
        margin-top: -2px;
        margin-bottom: 11px;
        }
        .invoice .info-card ul {
        margin-bottom: -6px;
        }
        .invoice .info-card ul li {
        font-family: "Lato", sans-serif;
        font-size: 14px;
        line-height: 1.6;
        }
       .invoice .info-card ul li span {
        font-weight: 600;
        margin-right: 3px;
        }
        .invoice .table {
        font-size: 14px;
        letter-spacing: 0.5px;
        }
        .invoice .table th {
        font-weight: 600;
        padding: 10px;
        }
        .invoice .table td {
        padding: 10px;
        }
        .invoice .total-payment-area ul {
        margin-top: -9px;
        padding: 0;
        margin-bottom: 0;
        }
        .invoice .total-payment-area ul li {
        font-size: 16px;
        line-height: 2;
        }
        .invoice .invoice-note {
        text-align: center;
        font-size: 14px;
        line-height: 1.5;
        margin-top: -15px;
        margin-bottom: -6px !important;
        }
        .invoice-table th {
            padding: 10px;
        }
        .invoice-table td {
            padding: 10px;
        }
        .invoice .table {
            color: #797979;
        }

        .invoice .table {
            font-size: 14px;
            letter-spacing: 0.5px;
            color: #c8d4f0;
        }
        .invoice .table {
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
            --bs-table-hover-bg: rgba(0, 0, 0, 0.03);
            color: #595959;
        }
    </style>
@endpush
@section('title', 'Billing - Invoice')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>{{ __('Billing') }}</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
        </div>
    </div>
    <div class="p-1">
        <div class="row g-4 justify-content-center">
            <div class="col-12">
                <div class="card rounded-0">
                    <div class="card-body invoice" id="invoiceBody">
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
                                {{-- <div class="col-sm-6">
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
                                </div> --}}
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
                                    <div class="col-md-6 col-sm-6">
                                        <div class="info-card float-end">
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
@endsection
