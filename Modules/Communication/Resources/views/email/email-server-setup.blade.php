
@extends('layout.master')
@push('stylesheets')

<style>
    .top-menu-area ul li {display: inline-block; margin-right: 3px;}
    .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}

    .btnPB{
        /* padding-bottom: 15px */
    }
    .customFormDedign{
        margin: 20px
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
            <br> @lang('menu.back')
        </a>
    </div>
    <div class="p-3">
        <div class="card mb-3">
            <div class="card-header border-0">
                <strong>{{ __('Email Server Setup') }}</strong>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form id="mail_server_credential" action="{{ route('communication.email.server.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-area customFormDedign">
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">Server</label>
                                    <div class="col-9">
                                        <input name="server_name" id="server_name" class="form-control" type="text" placeholder="E.x. smtp">
                                        <input name="mail_server_primary_id" id="mail_server_primary_id" type="hidden">
                                        <span class="error error_server_name"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">Host</label>
                                    <div class="col-9">
                                        <input name="host" id="host" name="" id="" class="form-control" type="text" placeholder="E.x. smtp.gmail.com">
                                        <span class="error error_host"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-4">
                                    <label for="" class="col-3">Port</label>
                                    <div class="col-9">
                                        <input name="port" id="port" class="form-control" type="number" placeholder="E.x. 587">
                                        <span class="error error_port"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">@lang('menu.user_name')</label>
                                    <div class="col-9">
                                        <input name="user_name" id="user_name" class="form-control" type="text" placeholder="E.x. @username">
                                        <span class="error error_user_name"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">@lang('menu.password')</label>
                                    <div class="col-9">
                                        <input name="password" id="password" class="form-control" type="password" placeholder="E.x. ************">
                                        <span class="error error_password"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">Encryption</label>
                                    <div class="col-9">
                                        <input name="encryption" id="encryption" class="form-control" type="text" placeholder="E.x. Tls/Ssl">
                                        <span class="error error_encryption"></span>
                                    </div>
                                </div>

                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">Sender Mail</label>
                                    <div class="col-9">
                                        <input name="address" id="address" class="form-control" type="text" placeholder="E.x. xyz@example.com">
                                        <span class="error error_address"></span>
                                    </div>
                                </div>
                                <div class="row g-3 mb-1">
                                    <label for="" class="col-3">Sender Name</label>
                                    <div class="col-9">
                                        <input name="name" id="name" class="form-control" type="text" placeholder="E.x. Mr. xyz">
                                        <span class="error error_name"></span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 btnPB">
                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                                    <button type="button" id="resetForm" class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                    <button type="submit" class="btn btn-sm btn-success">@lang('menu.save')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
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
                        <form id="all_delete_form" action="{{ route('communication.email.server.delete_all') }}" method="post">
                            @csrf
                            <table class="display data_tbl data__table emailBodyTable">
                                <thead>
                                    <tr>
                                        <th>Check</th>
                                        <th>Active</th>
                                        <th>Server</th>
                                        <th>Host</th>
                                        <th>Port</th>
                                        <th>User Name</th>
                                        <th>Password</th>
                                        <th>Address</th>
                                        <th>Name</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>

                                <tbody></tbody>

                            </table>
                        </form>
                        </div>
                    </div>
                </div>
            </div>


            <form id="deleted_form" action="" method="post">
                @method('DELETE')
                @csrf
            </form>

        </div>
    </div>
</div>

@endsection
@push('scripts')

<script type="text/javascript">

    var email_table = $('.data_tbl').DataTable({
        "processing": true,

        "pageLength": parseInt("{{ $generalSettings['system__datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('communication.email.server-setup') }}",
        },
        columns: [
            {data: 'check', name: 'check'},
            {data: 'status', name: 'status'},
            {data: 'server_name', name: 'server_name'},
            {data: 'host', name: 'host'},
            {data: 'port', name: 'port'},
            {data: 'user_name', name: 'user_name'},
            {data: 'password', name: 'password'},
            {data: 'address', name: 'address'},
            {data: 'name', name: 'name'},
            {data: 'edit', name: 'edit'},
            {data: 'delete', name: 'Delete'},

        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });


    $('#mail_server_credential').on('submit', function(e) {
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

                $('#server_name').val("");
                $('#host').val("");
                $('#port').val("");
                $('#user_name').val("");
                $('#password').val("");
                $('#address').val("");
                $('#encryption').val("");
                $('#name').val("");
                $('#mail_server_primary_id').val("");

                $('.emailBodyTable').DataTable().ajax.reload();
                $('.loading_button').hide();
            },
            error: function(err) {

                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
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


    $('#resetForm').on('click', function(e){
        $('#server_name').val("");
        $('#host').val("");
        $('#port').val("");
        $('#user_name').val("");
        $('#password').val("");
        $('#address').val("");
        $('#encryption').val("");
        $('#name').val("");
        $('#mail_server_primary_id').val("");
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
                    toastr.error('Net Connetion Error. Reload This Page.');
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


    $('#check_all').on('click', function () {

        console.log('CLICKED');
        if ($('#is_check_all').is(":checked")) {

            $('#is_check_all').prop("checked", false);
        } else {

            $('#is_check_all').prop("checked", true);
        }

        if ($('#is_check_all').is(":checked")) {

            $('.check1').prop('checked', true);
        }else{

            $('.check1').prop('checked', false);
        }
    });

    $(document).ready(function(){

        $(document).on('click', '#status',function(e){
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

                        toastr.error('Net Connetion Error. Reload This Page.');
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



    $(document).ready(function(){

        $(document).on('click', '#emailServerEdit',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    toastr.success(data.status);
                    $('.loading_button').hide();
                    $('#server_name').val(data.serverCredentialVal.server_name);
                    $('#host').val(data.serverCredentialVal.host);
                    $('#port').val(data.serverCredentialVal.port);
                    $('#user_name').val(data.serverCredentialVal.user_name);
                    $('#password').val(data.serverCredentialVal.password);
                    $('#address').val(data.serverCredentialVal.address);
                    $('#encryption').val(data.serverCredentialVal.encryption);
                    $('#name').val(data.serverCredentialVal.name);
                    $('#mail_server_primary_id').val(data.serverCredentialVal.id);

                    $('.emailBodyTable').DataTable().ajax.reload();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
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


    // delete

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#deleted_form').submit();
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

    $(document).on('submit', '#deleted_form', function(e) {
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
