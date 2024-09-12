@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/asset/css/richtext.min.css') }}">
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css" />


    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }


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
                <br> {{ __('Back') }}
            </a>
        </div>
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong>{{ __('Manual Email Service') }}</strong>
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
                                                {{ __('Specific Number') }}?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 d-flex justify-content-end">
                                        <button class="btn btn-sm btn-success">@lang('menu.send_mail')</button>
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
    <script src="{{ asset('backend/asset/js/jquery.richtext.min.js') }}"></script>
    <script type="text/javascript">
        $('.text-editor').richText();

        $('.specific-number-field').hide();
        $('#specificNumberCheck').on('change', function() {
            if ($(this).is(':checked')) {
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
