@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/asset/css/select2.min.css') }}" />
    <style>
        .parent a {
            font-size: 14px !important;
        }

        span.select2-results ul li {
            font-size: 15px;
            font-weight: 450;
            line-height: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-weight: 700;
        }

        .fw-icon {
            font-size: 11px;
            font-weight: 400;
        }

        .add_btn_frm_group:hover {
            color: rgb(21, 255, 21) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .delete_group_btn:hover {
            color: rgb(244, 16, 16) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .parent #parentText {
            text-transform: uppercase !important;
            font-weight: 700 !important;
            font-size: 17px !important;
        }

        .jstree-default .jstree-anchor {
            font-size: 11px !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush

@section('title', 'Account Groups - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h6>{{ __('Account Groups') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-6">
                                    <h6>{{ __('List Of Account Groups') }}</h6>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ route('account.groups.create') }}" class="btn btn-sm btn-primary" id="addAccountGroupBtn"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="card-body" id="list_of_groups">

                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add/Edit Account Group modal-->
    <div class="modal fade" id="accountGroupAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account Group modal End-->

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
        var lastChartListClass = '';

        $(document).on('click', '#addAccountGroupBtn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var group_id = $(this).data('group_id');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountGroupAddOrEditModal').html(data);
                    $('#accountGroupAddOrEditModal').modal('show');

                    $('#parent_group_id').val(group_id).trigger('change');

                    var is_allowed_bank_details = $('#parent_group_id').find('option:selected').data('is_allowed_bank_details');
                    $('#is_allowed_bank_details').val(is_allowed_bank_details);
                    var is_default_tax_calculator = $('#parent_group_id').find('option:selected').data('is_default_tax_calculator');
                    $('#is_default_tax_calculator').val(is_default_tax_calculator);

                    setTimeout(function() {

                        $('#account_group_name').focus();
                    }, 500);
                }
            })
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#editAccountGroupBtn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            lastChartListClass = $(this).data('class_name');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountGroupAddOrEditModal').empty();
                    $('#accountGroupAddOrEditModal').html(data);
                    $('#accountGroupAddOrEditModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#account_group_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        function getAjaxList() {

            $('.data_preloader').show();
            var branch_id = $('#f_branch_id').val();
            $.ajax({
                url: "{{ route('account.groups.list') }}",
                async: true,
                type: 'get',
                data: { branch_id },
                success: function(data) {

                    var div = $('#list_of_groups').html(data);

                    if (lastChartListClass) {

                        var scrollTo = $('.' + lastChartListClass);
                        scrollTo.addClass('jstree-clicked');

                        $('html, body').animate({

                            scrollTop: scrollTo.offset().top - 500
                        }, 0);
                    }

                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        }
        getAjaxList();

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            getAjaxList();
        });


        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
                        'action': function() {}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        lastChartListClass = '';
                        getAjaxList();
                        $("#parent_group_id").load(location.href + " #parent_group_id>*", "");
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    } else {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    </script>
@endpush
