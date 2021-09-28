@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {font-size: 12px !important;}
        .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 94%;z-index: 9999999;padding: 0;left: 3%;display: none;border: 1px solid #7e0d3d;margin-top: 1px;border-radius: 0px;}
        .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
        .select_area ul li a {color: #000000;text-decoration: none;font-size: 13px;padding: 4px 3px;display: block;}
        .select_area ul li a:hover {background-color: #ab1c59;color: #fff;}
        .selectProduct{background-color: #ab1c59; color: #fff!important;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form id="edit_purchase_form" action="{{ route('purchases.update') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $purchaseId }}">
                <input type="hidden" name="paid" id="paid" value="">
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="py-2 px-2 form-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Edit Purchase</h5>
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
                                                    <input readonly type="text" id="supplier_name" class="form-control">
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
                                                        class="text-danger">*</span> <b>Branch :</b> </label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].' (HO)' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Invoice ID :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="invoice_id" id="invoice_id" class="form-control">
                                                </div>
                                            </div>

                                            @if (json_decode($generalSettings->purchase, true)['is_enable_status'] == '1')
                                                <div class="input-group mt-1">
                                                    <label for="inputEmail3" class=" col-4"><b>Status :</b></label>
                                                    <div class="col-8">
                                                        <select class="form-control changeable" name="purchase_status" id="purchase_status">
                                                            <option value="1">Received</option>
                                                            <option value="2">Pending</option>
                                                            <option value="3">Ordered</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @else
                                                <input type="hidden" name="purchase_status" id="purchase_status" value="1">
                                            @endif
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Date :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="date" class="form-control changeable"
                                                         id="datepicker" value="{{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($purchase->date)) }}">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Attachment :</b> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Purchase related any file.Ex: Scanned cheque, payment prove file etc. Max File Size 2MB." class="fas fa-info-circle tp"></i></label>
                                                <div class="col-8">
                                                    <input type="file" class="form-control" name="attachment">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Pay Term :</b></label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <input type="text" name="pay_term_number" class="form-control w-25"
                                                            id="pay_term_number">
                                                        <select name="pay_term" class="form-control w-75 changeable"
                                                            id="pay_term">
                                                            <option value="">Select Pay Term</option>
                                                            <option value="1">Days</option>
                                                            <option value="2">Months</option>
                                                        </select>
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

                <section>
                    <div class="sale-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-details-sec">
                                    <div class="content-inner">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="searching_area" style="position: relative;">
                                                    <label for="inputEmail3" class="col-form-label">Item Search</label>
                                                    <div class="input-group ">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-barcode text-dark"></i></span>
                                                        </div>
                                                        <input type="text" name="search_product" class="form-control scanable" autocomplete="off" id="search_product" placeholder="Search Product by product code(SKU) / Scan bar code">
                                                        <div class="input-group-prepend">
                                                            <span id="add_product" class="input-group-text add_button"><i class="fas fa-plus-square text-dark"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="select_area">
                                                        <ul id="list" class="variant_list_area">
                                                           
                                                        </ul>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="sale-item-sec">
                                                <div class="sale-item-inner">
                                                    <div class="table-responsive">
                                                        <table class="display data__table table-striped">
                                                            <thead class="staky">
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Quantity</th>
                                                                    <th>Unit Cost(Before Discount)</th>
                                                                    <th>Discount</th>
                                                                    <th>Unit Cost(Before Tax)</th>
                                                                    <th>SubTotal (Before Tax)</th>
                                                                    <th>Unit Tax</th>
                                                                    <th>Net Unit Cost</th>
                                                                    <th>Line Total</th>
                                                                    @if (json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1')
                                                                        <th>Profit Margin(%)</th>
                                                                        <th>Selling Price</th>
                                                                    @endif
                                                                    <th><i class="fas fa-trash-alt"></i></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="purchase_list">
                                                               
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
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Discount :</b></label>
                                                <div class="col-8">
                                                    <div class="row">
                                                        <select name="order_discount_type" class="form-control w-25" id="order_discount_type">
                                                            <option value="1">Fixed</option>
                                                            <option value="2">Percentage</option>
                                                        </select>

                                                        <input name="order_discount" type="number" class="form-control w-75" id="order_discount" value="0.00"> 
                                                    </div>
                                                    <input name="order_discount_amount" type="number" step="any" class="d-none" id="order_discount_amount" value="0.00"> 
                                                </div>
                                            </div>

                                        
                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class="col-4"><b>Tax :</b></label>
                                                <div class="col-8">
                                                    <select name="purchase_tax" class="form-control" id="purchase_tax">
                                                        <option value="0.00">NoTax</option>
                                                    </select>
                                                    <input name="purchase_tax_amount" type="number" step="any" class="d-none" id="purchase_tax_amount" value="0.00">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Ship Cost :</b> </label>
                                                <div class="col-8">
                                                    <input name="shipment_charge" type="number" class="form-control" id="shipment_charge" value="0.00"> 
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Ship Details :</b></label>
                                                <div class="col-8">
                                                    <input name="shipment_details" type="text" class="form-control" id="shipment_details" placeholder="Shipment Details"> 
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4">Total Item:</label>
                                                <div class="col-8">
                                                    <input readonly name="total_item" type="number" step="any" class="form-control" id="total_item" value="0.00">
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>Order Note :</b></label>
                                                <div class="col-8">
                                                    <input type="text" name="purchase_note" id="purchase_note" class="form-control" value="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="inputEmail3" class=" col-4"><b>Net Total :</b>  {{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly name="net_total_amount" type="number" step="any" id="net_total_amount" class="form-control" value="0.00" >
                                                </div>
                                            </div>

                                            <div class="input-group mt-1">
                                                <label for="inputEmail3" class=" col-4"><b>payable :</b>{{ json_decode($generalSettings->business, true)['currency'] }}</label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="total_purchase_amount" id="total_purchase_amount" class="form-control" value="0.00">
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

    <!--Add Product Modal--> 
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_product_body">
                    
                </div>
            </div>
        </div>
    </div> 
    <!--Add Product Modal End-->
@endsection
@push('scripts')
    @include('purchases.partials.purchaseEditJsScript')
@endpush