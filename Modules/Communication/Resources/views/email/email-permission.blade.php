@extends('layout.master')
@push('stylesheets')



<link rel="stylesheet" href="{{ asset('backend/asset/css/richtext.min.css') }}">
<link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>


<style>
    .top-menu-area ul li {display: inline-block; margin-right: 3px;}
    .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}


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
@section('title', 'Email Settings - ')
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>Email Setup & Settings</h6>
        <a href="http://erp.test/communication/email/settings" class="btn text-white btn-sm float-end d-lg-block d-none">
            <i class="fa-thin fa-left-to-line fa-2x"></i>
            <br> Back
        </a>
    </div>
    <div class="p-3">
        <div class="card mb-3">
            <div class="card-header border-0">
                <strong>Email Permission on Module</strong>
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
                                                    {{ __('Due Collection') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="duePaymentCheck">
                                                <label class="form-check-label" for="duePaymentCheck">
                                                    {{ __('Due Payment') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="returnCollectionCheck">
                                                <label class="form-check-label" for="returnCollectionCheck">
                                                    {{ __('Return Collection') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="returnPaymentCheck">
                                                <label class="form-check-label" for="returnPaymentCheck">
                                                    @lang('menu.return_payment')
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
                                    <label for="">{{ __('Format Name') }}</label>
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
                                        <td>{{ __('Format Name') }}</td>
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
</div>

@endsection
@push('scripts')
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
