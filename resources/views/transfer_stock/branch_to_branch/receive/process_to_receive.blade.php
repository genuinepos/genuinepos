@extends('layout.master')
@push('stylesheets')
    <style>
        .input-group-text {
            font-size: 12px !important;
        }

    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-exchange-alt"></span>
                    <h6>@lang('menu.process_to_receive_stock') <small>(@lang('menu.transferred_from_another_location'))</small></h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>
        </div>
        <div class="p-3">
            <form id="receive_stock_form" action="{{ route('transfer.stock.branch.to.branch.ProcessToReceive.save', $transfer->id) }}" method="POST">
                @csrf
                <section>
                    <div class="form_element rounded mt-0 mb-3">

                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="m-0"><strong>@lang('menu.transfer_reference_id') : </strong> {{ $transfer->ref_id }}</p>
                                    <p class="m-0"><strong>@lang('menu.date')</strong> {{ $transfer->date }}</p>
                                    </div>

                                    <div class="col-md-6">
                                        <p class="m-0"><strong>{{ __('Transferred From') }}</strong>

                                        @if ($transfer->sender_branch)

                                            {{ $transfer->sender_branch->name.'/'.$transfer->sender_branch->branch_code }}
                                        @else

                                            {{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))
                                        @endif
                                    </p>
                                    </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-4">@lang('menu.store_in_location') : </label>
                                        <div class="col-8">
                                            <input
                                                readonly
                                                type="text"
                                                name="receiver_branch_id"
                                                id="receiver_branch_id"
                                                class="form-control"
                                                value="{{ $transfer->receiver_branch ? $transfer->receiver_branch->name.'/'.$transfer->receiver_branch->branch_code : $generalSettings['business__shop_name'] }}"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="input-group">
                                        <label class="col-4">@lang('menu.store_in_warehouse') : <i data-bs-toggle="tooltip" data-bs-placement="right" title="If you keep this field empty, Received stock will be added to Business Location/Shop" class="fas fa-info-circle tp"></i></label>
                                        <div class="col-8">
                                            <select name="receiver_warehouse_id" class="form-control" id="receiver_warehouse_id" autofocus>
                                                <option value="">@lang('menu.none')</option>
                                                @foreach ($warehouses as $w)
                                                    <option {{ $transfer->receiver_warehouse_id == $w->id ? 'SELECTED' : '' }} value="{{ $w->id }}">{{ $w->warehouse_name.'/'.$w->warehouse_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="sale-content mb-3">
                        <div class="card">
                            <div class="card-body px-2">
                                <div class="table-responsive">
                                    <table class="display data__table table-striped">
                                        <thead class="staky">
                                            <tr>
                                                <th>@lang('menu.product')</th>
                                                <th>@lang('menu.send_quantity')</th>
                                                <th>@lang('menu.unit')</th>
                                                <th>@lang('menu.pending_qty')</th>
                                                <th>@lang('menu.receive_quantity')</th>
                                            </tr>
                                        </thead>
                                        <tbody id="send_stock_list">
                                            @foreach ($transfer->transfer_products as $transfer_product)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="product_ids[]" value="{{ $transfer_product->product->id }}">
                                                        <input
                                                            type="hidden"
                                                            name="variant_ids[]"
                                                            value="{{ $transfer_product->variant ? $transfer_product->variant->id : 'no_id' }}"
                                                        >
                                                        {{ $transfer_product->product->name }}
                                                        {{ $transfer_product->variant ? '/'.$transfer_product->variant->variant_name : '' }}
                                                    </td>

                                                    <td>
                                                        <input type="hidden" id="qty_limit" value="{{ $transfer_product->send_qty }}">
                                                        {{ $transfer_product->send_qty }}
                                                    </td>

                                                    <td>
                                                        {{ $transfer_product->product->unit->name }}
                                                    </td>

                                                    <td>
                                                        <input
                                                            type="hidden"
                                                            name="pending_quantities[]"
                                                            id="pending_qty"
                                                            value="{{ $transfer_product->pending_qty }}"
                                                        >
                                                        <span id="span_pending_qty">{{ $transfer_product->pending_qty }}</span>
                                                    </td>

                                                    <td>
                                                        <input type="number"
                                                            step="any"
                                                            name="received_quantities[]"
                                                            class="form-control"
                                                            id="received_qty"
                                                            value="{{ $transfer_product->received_qty }}"
                                                            autocomplete="off"
                                                        >
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="form_element rounded mt-0 mb-3">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class="col-4">{{ __('Receiver Note') }} </label>
                                        <div class="col-8">
                                            <input type="text" name="receiver_note" id="receiver_note" class="form-control" placeholder="{{ __('Receiver Note') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4">@lang('menu.total') @lang('menu.received_qty') : </label>
                                        <div class="col-8">
                                            <input readonly type="number" step="any" name="total_received_quantity" id="total_received_quantity" class="form-control" value="0.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label class=" col-4">{{ __('Total Pending Qty') }} </label>
                                        <div class="col-8">
                                            <input readonly type="number" step="any" name="total_pending_quantity" id="total_pending_quantity" class="form-control" value="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="btn-loading">
                            <button type="button" class="btn loading_button d-hide">
                                <i class="fas fa-spinner"></i><span> @lang('menu.loading')...</span>
                            </button>
                            <button type="submit" id="save" class="btn btn-sm btn-success">@lang('menu.save') (Ctrl + Enter)</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
     // Calculate total amount functionalitie
     calculateTotalAmount();

     function calculateTotalAmount(){
        var received_quantities = document.querySelectorAll('#received_qty');
        var pending_quantities = document.querySelectorAll('#pending_qty');
        // Update Total Item
        var total_received_qty = 0
        received_quantities.forEach(function(qty){

            total_received_qty += parseFloat(qty.value ? qty.value : 0);
        });

        $('#total_received_quantity').val(parseFloat(total_received_qty).toFixed(2));

        var total_pending_qty = 0
        pending_quantities.forEach(function(qty){

            total_pending_qty += parseFloat(qty.value ? qty.value : 0);
        });

        $('#total_pending_quantity').val(parseFloat(total_pending_qty).toFixed(2));
    }

     // Quantity increase or dicrease and clculate row amount
     $(document).on('input', '#received_qty', function(){

        var qty = $(this).val() ? $(this).val() : 0;

        if (parseFloat(qty) >= 0) {

            var tr = $(this).closest('tr');
            var qty_limit = tr.find('#qty_limit').val();
            var unit = tr.find('#unit').val();
            var pending_qty = parseInt(qty_limit) - parseFloat(qty);

            tr.find('#pending_qty').val(parseFloat(pending_qty).toFixed(2));
            tr.find('#span_pending_qty').html(parseFloat(pending_qty).toFixed(2));

            if(parseInt(qty) > parseInt(qty_limit)){
                $(this).val(qty_limit);
                tr.find('#pending_qty').val(parseFloat(0).toFixed(2));
                tr.find('#span_pending_qty').html(parseFloat(0).toFixed(2));
                calculateTotalAmount();
                alert('Only - '+qty_limit+' '+unit+' is available.');
            }

            calculateTotalAmount();
        }
    });

    //Add purchase request by ajax
    $('#receive_stock_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                if(!$.isEmptyObject(data.errorMsg)){

                    toastr.error(data.errorMsg,'ERROR');
                    $('.loading_button').hide();
                }else{

                    $('.loading_button').hide();
                    toastr.success(data.successMsg);
                }
            }, error: function(err) {

                $('.loading_button').hide();

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                }else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }
            }
        });
    });

    document.onkeyup = function () {
        var e = e || window.event; // for IE to cover IEs window event-object

        if(e.ctrlKey && e.which == 13) {

            $('#save').click();
            return false;
        }
    }
</script>
@endpush
