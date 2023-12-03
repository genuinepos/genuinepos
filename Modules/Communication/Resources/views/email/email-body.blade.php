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

        .data__table thead tr th.text-center {
            text-align: center !important;
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
                    <strong>Email Body Format</strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form id="mail_body_format" action="{{ route('communication.email.body-format.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2 mb-3">

                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">{{ __('Format Name') }}</label>
                                            <div class="col-8">
                                                <input type="text" name="format_name" id="format_name" class="form-control" placeholder="{{ __('Format Name') }}">
                                                <span class="error error_format_name"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="row g-2">
                                            <label for="" class="col-4">{{ __('Subject') }}</label>
                                            <div class="col-8">
                                                <input type="text" name="mail_subject" id="mail_subject" class="form-control" placeholder="{{ __('Email Subject') }}">
                                                <span class="error error_mail_subject"></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <textarea name="body_format" class="text-editor" id="body_format"></textarea>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                                        <button type="submit" class="c-btn button-success me-0 float-end submit_button mr-1">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('menu.reset')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">

                            <div class="rich_data_view" id="rich_data_view"></div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="mailbox-controls">
                                <button type="button" class="btn btn-default checkbox-toggle" id="check_all"><i class="far fa-square"></i></button>
                                <input type="checkbox" id="is_check_all" class="d-none">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default all_delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive mailbox-messages">
                                <form id="all_delete_form" action="{{ route('communication.email.body.delete_all') }}" method="post">
                                    @csrf
                                    <table class="display data_tbl data__table emailBodyTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Check</th>
                                                <th class="text-center">Importent</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Subject</th>
                                                <th class="text-center">Delete</th>
                                                <th class="text-center">View</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="delete_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('backend/asset/js/jquery.richtext.min.js') }}"></script>
    <script type="text/javascript">
        $('.text-editor').richText();

        var email_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ $generalSettings['system__datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.email.body') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'format_name',
                    name: 'format_name'
                },
                {
                    data: 'mail_subject',
                    name: 'mail_subject'
                },
                {
                    data: 'delete',
                    name: 'Delete'
                },
                {
                    data: 'view',
                    name: 'view'
                },

            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        $(document).ready(function() {

            $(document).on('click', '#status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.loading_button').hide();
                        $('.emailBodyTable').DataTable().ajax.reload();

                        toastr.success(data);
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server error. Please contact to the support team.');
                            return;
                        }

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });



        $(document).ready(function() {

            $(document).on('click', '#emailBodyView', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        toastr.success(data.status);
                        $('.loading_button').hide();
                        $('.rich_data_view').html(data.template.body_format);

                        $('#body_format').val(data.template.body_format);

                        $('#format_name').val(data.template.format_name);
                        $('#mail_subject').val(data.template.mail_subject);


                        $('.emailBodyTable').DataTable().ajax.reload();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                            return;
                        }

                        $.each(err.responseJSON.errors, function(key, error) {

                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });


        $('#mail_body_format').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.loading_button').hide();
                    $('.rich_data_view').html(data.template.body_format);
                    // toastr.success(data.success);
                    $('.emailBodyTable').DataTable().ajax.reload();
                    // $('#format_name').val('');
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });


        $('.all_delete').on('click', function() {

            $('#all_delete_form').submit();
        });

        $('#all_delete_form').on('submit', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.emailBodyTable').DataTable().ajax.reload();

                    toastr.success(data);
                },
                error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    if (err.status == 0) {
                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {
                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });


        $('#check_all').on('click', function() {

            console.log('CLICKED');
            if ($('#is_check_all').is(":checked")) {

                $('#is_check_all').prop("checked", false);
            } else {

                $('#is_check_all').prop("checked", true);
            }

            if ($('#is_check_all').is(":checked")) {

                $('.check1').prop('checked', true);
            } else {

                $('.check1').prop('checked', false);
            }
        });


        // delete

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-modal-primary',
                        'action': function() {
                            $('#delete_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {
                            console.log('Deleted canceled.');
                        }
                    }
                }
            });
        });

        $(document).on('submit', '#delete_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    email_table.ajax.reload();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }
                    toastr.success(data.responseJSON);
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    email_table.ajax.reload();
                }
            });
        });
    </script>
@endpush
