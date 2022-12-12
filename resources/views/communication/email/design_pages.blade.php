@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/asset/css/richtext.min.css') }}">
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .richText .richText-editor {
            border-left: 0;
        }
        .richText .richText-editor:focus {
            border-left: 0;
        }
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
@section('title', 'Email Setup Design Pages - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-university"></span>
                    <h5>Email Design Pages</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>Email Server Setup</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="form-area">
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Server</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="E.x. smtp">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Host</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="E.x. smtp.gmail.com">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-4">
                                        <label for="" class="col-3">Port</label>
                                        <div class="col-9">
                                            <input class="form-control" type="number" placeholder="E.x. 587">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">@lang('menu.user_name')</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="E.x. @username">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">@lang('menu.password')</label>
                                        <div class="col-9">
                                            <input class="form-control" type="password" placeholder="E.x. ************">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Sender</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="E.x. abc@example.com">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">Subject</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="E.x. Invoice">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">CC</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="xyz@example.com,asdf@example.com">
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-1">
                                        <label for="" class="col-3">BCC</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" placeholder="xyz@example.com,asdf@example.com">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                        <button class="btn btn-sm btn-success">@lang('menu.save')</button>
                                    </div>
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
                                            <td>@lang('menu.serial')</td>
                                            <td>Sender</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
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
                    <strong>Email Body Format</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row g-2 mb-3">
                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">Format Name</label>
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="Format Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 d-flex justify-content-end">
                                        <a role="button" class="btn btn-sm btn-success">Format List & Settings</a>
                                    </div>
                                </div>
                                <textarea class="text-editor" name="example"></textarea>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <p class="text-secondary"><strong>Example:</strong></p>
                            <strong>Dear Customer,</strong>
                            <p>THis is a notice that invoice @invoice which was originally generated on @date.</p>
                            <br>
                            <p><strong>@lang('menu.date') #</strong> @date</p>
                            <p><strong>Invoice #</strong> @invoice</p>
                            <hr class="my-1">
                            <p><strong>Sub Total :</strong> @subtotal</p>
                            <p><strong>vat :</strong> @vat</p>
                            <p><strong>@lang('menu.total_due') :</strong> @due</p>
                            <p><strong>Total Discount :</strong> @discount</p>
                            <p><strong>@lang('menu.total') :</strong> @total_amount</p>
                            <br>
                            <p><strong>Reward Points :</strong> @point</p>
                            <br>
                            <p><strong>Thank you for staying with us.</strong></p>
                            <br>
                            <p>Regards</p>
                            <p>@lang('menu.user')</p>
                            <h4>@lang('menu.company')</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>@lang('menu.email_settings')</strong>
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
                                                        POS Sale
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="" id="addSaleCheck">
                                                    <label class="form-check-label" for="addSaleCheck">
                                                        Add Sale
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
                                            <td>Sender</td>
                                            <td>Format Name</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
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
                                        </tr>
                                        <tr>
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
                    <strong>Manual Email Service</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab_list_area">
                                <div class="btn-group">
                                    <a id="tab_btn" data-show="receiver-email" class="btn btn-sm btn-primary tab_btn tab_active" href="#">
                                        <i class="fas fa-scroll"></i> Receiver Email
                                    </a>

                                    <a id="tab_btn" data-show="blocked-email" class="btn btn-sm btn-primary tab_btn" href="#">
                                        <i class="fas fa-info-circle"></i> Blocked Email
                                    </a>
                                </div>
                            </div>
                            <div class="tab_contant receiver-email">
                                <form action="" class="mb-2">
                                    <div class="d-flex gap-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="customerCheck">
                                            <label class="form-check-label" for="customerCheck">
                                                Customer
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="supplierCheck">
                                            <label class="form-check-label" for="supplierCheck">
                                                Supplier
                                            </label>
                                        </div>
                                    </div>
                                    <input type="search" name="" id="" class="form-control" placeholder="Search Email">
                                </form>
                                <div class="table-responsive-y">
                                    <table class=" display table-striped">
                                        <tbody>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <span>Double click on email to block</span>
                            </div>
                            <div class="tab_contant blocked-email d-hide">
                                <form action="" class="mb-2">
                                    <input type="search" name="" id="" class="form-control" placeholder="Search Email">
                                </form>
                                <div class="table-responsive-y">
                                    <table class=" display table-striped">
                                        <tbody>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                            <tr>
                                                <td>abc@gmail.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@email.com</td>
                                            </tr>
                                            <tr>
                                                <td>email@example.com</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <span>Double click on email to unblock</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header py-1">
                            <span>Manual Mail</span>
                        </div>
                        <div class="card-body">
                            <form action="">
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <select name="" id="" class="form-control">
                                            <option value="">--Select Sender--</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="sendHtmlWithImageCheck">
                                            <label class="form-check-label" for="sendHtmlWithImageCheck">
                                                Send HTML with image
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" placeholder="CC: E.x. abs@example.com, xyz@wxample.com">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" placeholder="BCC: E.x. abs@example.com, xyz@wxample.com">
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control" type="text" placeholder="Email Subject">
                                    </div>
                                    <div class="col-12 mb-2">
                                        <textarea class="text-editor" name="example"></textarea>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="specificNumberCheck">
                                            <label class="form-check-label" for="specificNumberCheck">
                                                Specific Number?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <button class="btn btn-sm btn-success">Send Mail</button>
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

{{-- <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script> --}}
<script src="{{ asset('backend/asset/js/jquery.richtext.min.js') }}"></script>
<script type="text/javascript">

    $('.text-editor').richText();

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
