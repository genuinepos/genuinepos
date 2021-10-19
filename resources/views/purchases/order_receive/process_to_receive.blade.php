@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="receive_form" action="{{ route('purchases.po.receive.process.store', $purchase->id) }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Receive Purchase Order</h5>
                                        </div>

                                        <div class="col-6">
                                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Supplier :</b><span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input readonly type="text" id="supplier_name" class="form-control" value="{{ $purchase->supplier->name.' ('.$purchase->supplier->phone.')' }}">
                                                </div>
                                            </div>

                                            @if ($purchase->warehouse_id)
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class="col-4"><b>Warehouse :</b><span
                                                        class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable add_input"
                                                            name="warehouse_id" data-name="Warehouse" id="warehouse_id">
                                                            <option value="">Select Warehouse</option>
                                                            @foreach ($warehouses as $warehouse)
                                                                <option {{ $purchase->warehouse_id == $warehouse->id ? 'SELECTED' : '' }} value="{{ $warehouse->id }}">{{ $warehouse->warehouse_name.'/'.$warehouse->warehouse_code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="error error_warehouse_id"></span>
                                                    </div>
                                                </div>
                                            @else 
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><span
                                                        class="text-danger">*</span> <b>B.Location :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>PO.Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control" placeholder="Order Invoice ID" autocomplete="off" value="{{ $purchase->invoice_id }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-2"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input required type="text" name="date" class="form-control changeable"
                                                         id="datepicker" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-3"><b>Status :</b></label>
                                                <div class="col-8">
                                                    <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                        <option value="3">Ordered</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Ordered Qty</th>
                                                                    <th>Unit Cost(Inc.Tax)</th>
                                                                    <th>Subtotal</th>
                                                                    <th>Pending Qty</th>
                                                                    <th>Receive Qty</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="order_list">
                                                                @foreach ($purchase->purchase_order_products as $row)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" id="product_ids[]" value="{{ $row->product_id }}">
                                                                            <input type="hidden" id="variant_ids[]" value="{{ $row->product_variant_id ? $row->product_variant_id : 'noid' }}">
                                                                            {{ Str::limit($row->product->name, 25) }} 
                                                                            <b>{{ $row->variant ? ' - '.$row->variant->variant_name : '' }}</b>
                                                                        </td>

                                                                        <td> 
                                                                            <input type="hidden" name="ordered_quantities[]" id="ordered_quantity" value="{{ $row->order_quantity }}">
                                                                            <input type="hidden" id="unit" value="{{$row->unit}}">
                                                                            <b>{{ $row->order_quantity }} ({{$row->unit}})</b>
                                                                        </td>

                                                                        <td> <b>{{ $row->net_unit_cost }}</b></td>

                                                                        <td> <b>{{ $row->line_total }}</b></td>

                                                                        <td> 
                                                                            <input readonly type="text" class="form-control text-danger" name="pending_quantities[]" id="pending_quantity" value="{{ $row->pending_quantity }}">
                                                                        </td>

                                                                        <td> 
                                                                            <input required type="number" step="any" class="form-control" name="received_quantities[]" id="received_quantity" value="{{ $row->received_quantity }}">
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="hidden" name="total_pending" id="total_pending">
                                            <input type="hidden" name="total_received" id="total_received">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Discount :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="order_discount_amount" type="number" step="any" class="form-control" id="order_discount_amount" value="{{ $purchase->order_discount_amount }}"> 
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Tax :</b> </label>
                                                <div class="col-8">
                                                    <input readonly name="purchase_tax_amount" type="text" class="form-control" id="purchase_tax_amount" value="{{ $purchase->purchase_tax_amount.'('.$purchase->purchase_tax_percent.'%)' }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Ship Cost :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="{{ $purchase->shipment_charge }}"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Total Item :</b> </label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="{{ $purchase->total_item }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Paid :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="{{ $purchase->paid }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Net Total :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="total_purchase_amount" type="number" step="any" class="form-control" value="{{ $purchase->total_purchase_amount }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Due :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="due" id="due" class="form-control" value="{{ $purchase->due }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element m-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Paying :</b> {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input name="paying_amount" class="form-control" id="paying_amount" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Pay Note :</b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="payment_note" class="form-control" id="payment_note" placeholder="Payment note">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>Pay Method :</b> </label>
                                                <div class="col-8">
                                                    <select name="payment_method" class="form-control" id="payment_method">
                                                        <option value="Cash">Cash</option>
                                                        <option value="Advanced">Advanced</option>
                                                        <option value="Cheque">Cheque</option>
                                                        <option value="Card">Card</option>
                                                        <option value="Bank-Transfer">Bank-Transter</option>
                                                        <option value="Other">Other</option>
                                                        <option value="Custom">Custom Field</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>Account :</b> </label>
                                                <div class="col-8">
                                                    <select name="account_id" class="form-control" id="account_id">
                                                        <option value="">None</option>
                                                        @foreach ($accounts as $ac)
                                                            <option value="{{ $ac->id }}">{{ $ac->name.' (A/C: '.$ac->account_number.')' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Total Due :</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" class="form-control" name="purchase_due" id="purchase_due" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="submit_button_area pt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button class="btn btn-sm btn-primary submit_button float-end">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function calulateTotalReceiveAndPendingQty() {
            var pending_quantities = document.querySelectorAll('#pending_quantity');
            var received_quantities = document.querySelectorAll('#received_quantity');
            var total_pending = 0;
            pending_quantities.forEach(function(qty){
                total_pending += parseFloat(qty.value) ? parseFloat(qty.value) : 0;
            });

            var total_received = 0;
            received_quantities.forEach(function(qty){
                total_received += parseFloat(qty.value) ? parseFloat(qty.value) : 0;
            });

            $('#total_pending').val(parseFloat(total_pending));
            $('#total_received').val(parseFloat(total_received));
        }

        $(document).on('input', '#received_quantity', function(){
            var received_qty = $(this).val() ? $(this).val() : 0;
            if (parseFloat(received_qty) >= 0) {
                var tr = $(this).closest('tr');
                var ordered_quantity = tr.find('#ordered_quantity').val();
                var unit = tr.find('#unit').val();
                var pending_qty = parseInt(ordered_quantity) - parseFloat(received_qty);
                tr.find('#pending_quantity').val(parseFloat(pending_qty).toFixed(2));
                calulateTotalReceiveAndPendingQty();
                if(parseInt(received_qty) > parseInt(ordered_quantity)){
                    alert('Only - '+ordered_quantity+' '+unit+' is available.');
                    $(this).val(ordered_quantity);
                    tr.find('#pending_quantity').val(parseFloat(0).toFixed(2));
                    calulateTotalReceiveAndPendingQty();
                    return;
                }
            }
        });

        $(document).on('input', '#paying_amount',function () {
            var paying_amount = $(this).val() ? $(this).val() : 0;
        });

        //Add receive request by ajax
        $('#receive_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    toastr.success(data);
                },error: function(err) {
                    $('.submit_button').prop('type', 'sumbit');
                    $('.loading_button').hide();
                    $('.error').html('');
                    toastr.error('Net Connetion Error. Reload This Page.'); 
                }
            });
        });



        var dateFormat = "{{ json_decode($generalSettings->business, true)['date_format'] }}";
        var _expectedDateFormat = '' ;
        _expectedDateFormat = dateFormat.replace('d', 'DD');
        _expectedDateFormat = _expectedDateFormat.replace('m', 'MM');
        _expectedDateFormat = _expectedDateFormat.replace('Y', 'YYYY');
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: _expectedDateFormat,
        });
    </script>
@endpush