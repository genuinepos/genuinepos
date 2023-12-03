@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}/backend/asset/css/select2.min.css" />
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
    </style>
@endpush
@section('title', 'Email - index')
@section('content')
    <div class="body-wraper">
        <section class="mt-5x">
            <div class="container-fluid">
                <div class="row">
                    <div class="sec-name">
                        <h6>Email Section</h6>
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button">
                            <i class="fa-thin fa-left-to-line fa-2x"></i>
                            <br> Back
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <div class="content-wrapper p-15">
            <section class="content email-content">
                <div class="row g-1">
                    <div class="col-md-3">
                        <div class="card mail-sidebar">
                            <div class="card-body">
                                <button class="btn btn-default text-white btn-block rounded-pill w-auto px-4 mb-2" data-bs-toggle="modal" data-bs-target="#addCompose"><i class="fa-duotone fa-pen-fancy"></i> Compose</button>
                                <ul class="nav nav-pills flex-column">
                                    <li class="nav-item active">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-mailbox"></i></span> Inbox
                                            <span class="count">12</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-envelope"></i></span> Sent
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-file-lines"></i></span> Drafts
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-filters"></i></span> Junk
                                            <span class="count">65</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <span class="mail-icon"><i class="fa-duotone fa-trash-arrow-up"></i></span> Trash
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-9 mt-1">
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
                                    <form id="all_delete_form" action="{{ route('communication.email.delete_all') }}" method="post">
                                        @csrf
                                        <table class="display data_tbl data__table emailTable">
                                            <tbody></tbody>
                                        </table>
                                    </form>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        </div>
    </div>

    <div class="modal fade" id="addCompose" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 32%;width: 54%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">New Message</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">

                    <form id="mail_send" action="{{ route('communication.email.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-5">
                                <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addMoreButton"><i class="fas fa-plus"></i> Add More Emails</button>
                            </div>

                            <div class="col-7">
                                <select name="group_id[]" class="form-control select2" multiple="multiple">
                                    <option disabled>Open this select menu</option>
                                    @foreach ($filtered_contact_email as $email)
                                        <option value="{{ $email->group->id }}">{{ $email->group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" id="to_area">
                            <div class="col-md-12">
                                <label><strong>To</strong> <span class="text-danger">*</span></label>
                                <input required type="email" name="to[]" class="form-control add_input" data-name="To" id="email" placeholder="Email" />
                                <span class="error error_to"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Subject</strong> <span class="text-danger">*</span></label>
                                <input required type="text" name="subject" class="form-control add_input" data-name="Subject" id="subject" placeholder="Subject" />
                                <span class="error error_subject"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Attachment </strong></label>
                                <input type="file" name="file" class="form-control add_input" data-name="file" id="file" placeholder="Attachments" multiple />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Body </strong></label>
                                <textarea id="editor" name="description" id="description"></textarea>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                                <button type="submit" class="c-btn button-success me-0 float-end submit_button">@lang('menu.save')</button>
                                <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">@lang('menu.close')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <form id="delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        $('.select2').select2({
            placeholder: "Select Group",
            allowClear: true
        });
    </script>
    <script>
        var email_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ $generalSettings['system__datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.email.index') }}",
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
                    data: 'mail',
                    name: 'Mail'
                },
                {
                    data: 'subject',
                    name: 'Subject'
                },
                {
                    data: 'attachment',
                    name: 'Attachment'
                },
                {
                    data: 'delete',
                    name: 'Delete'
                },
                {
                    data: 'time',
                    name: 'time'
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


                        $('.emailTable').DataTable().ajax.reload();
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
        });

        $('#mail_send').on('submit', function(e) {

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

                    toastr.success(data);
                    $('.emailTable').DataTable().ajax.reload();
                    $('#addCompose').modal('hide');
                    $('#mail_send')[0].reset();
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
                    $('.emailTable').DataTable().ajax.reload();

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

        // adding more To fields
        var child = '';
        child += '<div class="col-md-12 mt-2">';
        child += '<div class="row">';
        child += '<div class="col-md-10">';
        child += '<input type="email" name="to[]" class="form-control add_input" data-name="To" id="email" placeholder="Email" />';
        child += '<span class="error error_to"></span>';
        child += '</div>';
        child += '<div class="col-md-2 text-end">';
        child += '<button class="btn btn-sm btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="padding:4px 20px">X</button>';
        child += '</div>';
        child += '</div>';
        child += '</div>';

        var addMoreButton = document.getElementById('addMoreButton');
        var warrantyContainer = document.getElementById('to_area');

        $('#addMoreButton').on('click', function(e) {
            e.preventDefault();
            $('#to_area').append(child);
        });

        // body-wraper
        var height = $(window).height() - 113;
        $('.email-content').height(height);

        $('.content-wrapper').bind('resize', function() {
            var width = $('.content-wrapper').width();
            $('.email-content').width(width);
        });
    </script>
@endpush
