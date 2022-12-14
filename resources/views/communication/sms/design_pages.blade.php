@extends('layout.master')
@push('stylesheets')
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .table-responsive-y {
            max-height: 350px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .form-check {
            padding: 0;
            gap: 5px
        }
        .form-check-input {
            margin-left: 0 !important;
            margin-top: -2px !important;
        }
    </style>

@endpush
@section('title', 'SMS Setup Design Pages - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-university"></span>
                    <h5>SMS Design Pages</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>SMS Setup</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="form-area">
                                    <div class="row g-3 mb-1">
                                        <div class="col-sm-9">
                                            <div class="row g-3">
                                                <label for="" class="col-4">Format Name</label>
                                                <div class="col-8">
                                                    <input class="form-control" type="text" placeholder="Format Name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 d-flex justify-content-end">
                                            <a role="button" class="btn btn-sm btn-primary">List</a>
                                        </div>
                                        <div class="col-12">
                                            <label for="smsBodyExample">SMS Body Example <i class="fas fa-info-circle"></i></label>
                                            <textarea class="form-control" name="" id="smsBodyExample" rows="8"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <div class="sms-example">
                                                <p class="text-secondary"><strong>Example:</strong></p>
                                                <p>@lang('menu.date') @date,</p>
                                                <p>Dear Customer,</p>
                                                <p>Today Bill No @invoice_no. Net amount @net_amount + vat (@vat) and discount @discount. Total Amount: @total_amount</p>
                                                <p>Earning Point: @point</p>
                                                <p>Thank you.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="reset" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                        <button class="btn btn-sm btn-success">@lang('menu.save')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <span>SMS APIs</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="">API Settings Name</label>
                                    <input type="text" class="form-control" placeholder="E.x. Provider Name">
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-between mb-1">
                                        <label for="">Get Balance</label>
                                        <div>
                                            <span class="balance-txt">0.00</span>
                                            <a roole="button" class="btn btn-sm btn-success">Test</a>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control">
                                    <p><small class="text-secondary">E.x. https://www.example.com/key</small></p>
                                </div>
                                <div class="col-12">
                                    <label for=""> Send SMS (API) <i class="fas fa-info-circle"></i></label>
                                    <textarea name="" id="" rows="4" class="form-control"></textarea>
                                    <p><small class="text-secondary">E.x. https://www.example.com/Key.contact=@reciever_number.senderId=@senderId.message=message</small></p>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-sm btn-primary">List</button>
                                    <button class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <span>SMS Status Code Display Message</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="">API Status Code</label>
                                    <input type="number" class="form-control" placeholder="E.x. 1001">
                                </div>
                                <div class="col-12">
                                    <label for="">Display Message</label>
                                    <textarea name="" id="" rows="3" class="form-control" placeholder="E.x. Insufficient balance"></textarea>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-sm btn-primary">List</button>
                                    <button class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>Module Wise SMS Activation</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-2">
                                        <label for="">Module</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="row g-1">
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="posSaleCheck">
                                                    <label class="form-check-label" for="posSaleCheck">
                                                        @lang('menu.pos_sales')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="addSaleCheck">
                                                    <label class="form-check-label" for="addSaleCheck">
                                                        @lang('menu.add_sale')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="saleReturnCheck">
                                                    <label class="form-check-label" for="saleReturnCheck">
                                                        Sale Return
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="addPurchaseCheck">
                                                    <label class="form-check-label" for="addPurchaseCheck">
                                                        Add Purchase
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="purchaseReturnCheck">
                                                    <label class="form-check-label" for="purchaseReturnCheck">
                                                        @lang('menu.purchase_return')
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="dueCollectionCheck">
                                                    <label class="form-check-label" for="dueCollectionCheck">
                                                        Due Collection
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="duePaymentCheck">
                                                    <label class="form-check-label" for="duePaymentCheck">
                                                        Due Payment
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="returnCollectionCheck">
                                                    <label class="form-check-label" for="returnCollectionCheck">
                                                        Return Collection
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="returnPaymentCheck">
                                                    <label class="form-check-label" for="returnPaymentCheck">
                                                        Return Payment
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-2">
                                        <label for="">Sender</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <select name="" id="" class="form-control">
                                            <option value="">--Select Sender Server--</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2 mb-2">
                                    <div class="col-sm-2">
                                        <label for="">Format Name</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <select name="" id="" class="form-control">
                                            <option value="">--Select Format--</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="sendAttachmentCheck">
                                            <label class="form-check-label" for="sendAttachmentCheck">
                                                Send attachment (memo)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="activeCheck">
                                            <label class="form-check-label" for="activeCheck">
                                                Active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                    <button class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table class=" display table-striped">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <td>@lang('menu.sl')</td>
                                            <td>Module</td>
                                            <td>API Settings</td>
                                            <td>Format</td>
                                            <td>@lang('menu.status')</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>Manual SMS Service</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab_list_area">
                                <div class="btn-group">
                                    <a id="tab_btn" data-show="receiver-number" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                        <i class="fas fa-scroll"></i> Receiver Number
                                    </a>

                                    <a id="tab_btn" data-show="blocked-number" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-info-circle"></i> Blocked Number
                                    </a>
                                </div>
                            </div>
                            <div class="tab_contant receiver-number">
                                <form action="" class="mb-2">
                                    <div class="d-flex flex-wrap gap-3 mb-2" style="row-gap: 0 !important">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="customerCheck">
                                            <label class="form-check-label" for="customerCheck">
                                                Customer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="customerAlternateCheck">
                                            <label class="form-check-label" for="customerAlternateCheck">
                                                Customer Alternat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="supplierCheck">
                                            <label class="form-check-label" for="supplierCheck">
                                                Supplier
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="supplierAlternatCheck">
                                            <label class="form-check-label" for="supplierAlternatCheck">
                                                Supplier Alternat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="membershipCheck">
                                            <label class="form-check-label" for="membershipCheck">
                                                Membership
                                            </label>
                                        </div>
                                    </div>
                                    <input type="search" name="" id="" class="form-control" placeholder="Search Contact">
                                </form>
                                <div class="table-responsive-y">
                                    <table class=" display table-striped">
                                        <tbody>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <span>Double click on number to block</span>
                            </div>
                            <div class="tab_contant blocked-number d-hide">
                                <form action="" class="mb-2">
                                    <input type="search" name="" id="" class="form-control" placeholder="Search Contect">
                                </form>
                                <div class="table-responsive-y">
                                    <table class=" display table-striped">
                                        <tbody>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                            <tr>
                                                <td>012 345 678 9</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <span>Double click on number to unblock</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header py-1">
                            <span>Manual SMS</span>
                        </div>
                        <div class="card-body">
                            <form action="">
                                <div class="row g-2">
                                    <div class="col-sm-1 col-2">API</div>
                                    <div class="col-sm-5 col-10">
                                        <select name="" id="" class="form-control">
                                            <option value="">--Select API Settings--</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <textarea class="form-control" rows="10" name="example"></textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="specificNumberCheck">
                                            <label class="form-check-label" for="specificNumberCheck">
                                                {{ __('Specific Number') }}?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <button class="btn btn-sm btn-success">Send SMS</button>
                                    </div>
                                    <div class="col-sm-6 specific-number-field">
                                        <input type="tel" class="form-control" placeholder="Number">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">

    $('.specific-number-field').hide();
    $('#specificNumberCheck').on('change', function() {
        if($(this).is(':checked')) {
            $('.specific-number-field').slideDown();
        } else {
            $('.specific-number-field').slideUp();
        }
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });

</script>
@endpush
