@extends('layout.master')
@push('stylesheets')
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/pace-master/themes/red/pace-theme-fill-left.css') }}"/> --}}
    <style>
        b {
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
        }

        th.task-name {
            width: 75%;
        }

        th.task-assign-to {
            width: 15%;
        }

        th.task-status {
            width: 10%;
        }

        .custom-modify {
            padding: 3px 5px !important;
            line-height: 31px !important;
        }

        .edit_task_name {
            min-width: 150px !important;
        }

        .task_area {
            min-width: 150px;
        }
    </style>
@endpush
@section('title', 'Manage Tasks - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Manage Task') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>

            <div class="p-lg-1 p-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-1">
                            <div class="card-body">
                                <i class="fas fa-paperclip ms-2"></i> <b class="text-danger">({{ $workspace->workspace_no }})</b> {{ $workspace->name }}
                                <div class="px-2">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <table class="display table table-sm">
                                                <tr>
                                                    <th class="text-end">{{ __('Start Date') }} : </th>
                                                    <td class="text-end">{{ date('d-m-Y', strtotime($workspace->start_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-end">{{ __('End Date') }} : </th>
                                                    <td class="text-end">{{ date('d-m-Y', strtotime($workspace->end_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-end">{{ __('Estimated Hour') }} : </th>
                                                    <td class="text-end">{{ $workspace->estimated_hours }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-4">
                                            <table class="display table table-sm">
                                                <tr>
                                                    <th class="text-end">{{ __('Assigned By') }} : </th>
                                                    <td class="text-end">{{ $workspace->createdBy->prefix . ' ' . $workspace->createdBy->name . ' ' . $workspace->createdBy->last_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="text-end">{{ __('Assigned To') }} : </th>
                                                    <td class="text-end">
                                                        @foreach ($workspace->users as $workspaceUser)
                                                            {{ $workspaceUser?->user?->prefix . ' ' . $workspaceUser?->user?->name . ' ' . $workspaceUser?->user?->last_name }},
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="col-md-4">
                                            <table class="display table table-sm">
                                                <tr>
                                                    <th class="text-end">{{ __('Prioriry') }} : </th>
                                                    <td class="text-end"> {{ $workspace->priority }}</td>
                                                </tr>

                                                <tr>
                                                    <th class="text-end">{{ __('Status') }} : </th>
                                                    <td class="text-end"> {{ $workspace->status }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="py-2 px-2 form-header">
                        <div class="col-md-12">
                            <form id="add_task_form" action="{{ route('workspaces.task.store') }}">
                                @csrf
                                <input type="hidden" name="workspace_id" id="workspace_id" value="{{ $workspace->id }}">
                                <div class="row g-2">
                                    <div class="col-md-8">
                                        <input required type="text" name="task_name" id="task_name" class="form-control form-control-sm" placeholder="{{ __('Wright task and press enter') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <select name="task_status" id="task_status" class="form-control form-control-sm">
                                            <option value="In-Progress">{{ __('In-Progress') }}</option>
                                            <option value="Pending">{{ __('Pending') }}</option>
                                            <option value="Completed">{{ __('Completed') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-success submit_button">{{ __('Add') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="widget_content px-1">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}...</h6>
                        </div>

                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                {{-- <thead class="d-hide">
                                    <tr class="bg-secondary">
                                        <th class="task-name text-white text-start">Task</th>
                                        <th class="task-assign-to text-white text-start">@lang('menu.assigned_to')</th>
                                        <th class="task-status text-white text-start">@lang('menu.status')</th>
                                    </tr>
                                </thead> --}}

                                <tbody id="task_list"></tbody>
                            </table>
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

@endsection
@push('scripts')
    {{-- <script src="{{ asset('assets/plugins/custom/pace-master/pace.min.js') }}"></script> --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Get all customer by ajax
        function task_list() {
            $('.data_preloader').show();
            //Pace.start();
            $.ajax({
                url: "{{ route('workspaces.task.list', $workspace->id) }}",
                type: 'get',
                success: function(data) {
                    $('#task_list').html(data);
                    $('.data_preloader').hide();
                    //Pace.stop();
                }
            });
        }
        task_list();

        $(document).on('submit', '#add_task_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'ERROR');
                        $('.loading_button').hide();
                    } else {

                        $('#add_task_form')[0].reset();
                        $('.loading_button').hide();
                        toastr.success(data);
                        task_list();
                    }
                }
            });
        });

        var previousValue = "";
        $(document).on('click', '#edit_task_btn', function(e) {

            e.preventDefault();

            $('.task_area').show();
            $('.edit_task_name').hide();
            $(this).closest('tr').find('.edit_task_name').show();
            previousValue = $(this).closest('tr').find('#edit_task_name').val();
            $(this).closest('tr').find('.task_area').hide();
            $(this).closest('tr').find('.edit_task_name').focus();
        });

        $(document).on('keyup', '#edit_task_name', function(e) {
            // if (e.key == "Enter") $('.save').click();
            // if (e.key == "Escape") $('.cancel').click();
            if (e.key == "Escape") {

                $('.task_area').show();
                $('.edit_task_name').hide();
                $(this).closest('tr').find('#edit_task_name').val(previousValue);
            } else if (e.key == "Enter") {

                $(this).closest('tr').find('.update_task_button').click();
            }
        });

        $(document).on('click', '.update_task_button', function() {

            var value = $(this).closest('tr').find('#edit_task_name').val();
            $(this).closest('tr').find('#task_name').html(value);
            var id = $(this).closest('tr').find('.task_area').data('id');

            $('.task_area').show();
            $('.edit_task_name').hide();

            $.ajax({
                url: "{{ route('workspaces.task.update') }}",
                type: 'post',
                data: {
                    id,
                    value
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': "{{ __('Confirmation') }}",
                'message': "{{ __('Are you sure?') }}",
                'buttons': {
                    'Yes': {
                        'class': 'yes bg-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no bg-danger',
                        'action': function() {
                            // alert('Deleted canceled.')
                        }
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
                        return;
                    }

                    task_list();
                    toastr.error(data);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connection Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                        return;
                    }

                    toastr.error(err.responseJSON.message);
                }
            });
        });

        $(document).on('click', '#assign_user', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var user_id = $(this).data('user_id');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#assign_user', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var user_id = $(this).data('user_id');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    user_id
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#change_status', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var status = $(this).data('status');
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    status
                },
                success: function(data) {

                    task_list();
                }
            });
        });

        $(document).on('click', '#change_priority', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            var priority = $(this).data('priority');

            $.ajax({
                url: url,
                type: 'get',
                data: {
                    priority
                },
                success: function(data) {

                    task_list();
                }
            });
        });
    </script>
@endpush
