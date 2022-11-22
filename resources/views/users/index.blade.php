@extends('layout.master')
@push('stylesheets') @endpush
@section('title', 'User List - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>@lang('menu.users')</h6>
                            </div>
                            <div class="d-flex">
                                <div id="exportButtonsContainer">
                                    @if (auth()->user()->can('user_add'))
                                        <a href="{{ route('users.create') }}" class="btn text-white btn-sm"><i class="fa-thin fa-circle-plus fa-2x"></i><br>New User</a>
                                    @endif
                                </div>
                                <a href="#" class="btn text-white btn-sm d-lg-block d-hide"><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                            </div>

                            <div>
                                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="p-15">

                    <div class="p-3">
                        @if ($addons->branches == 1)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <form action="" method="get" class="px-2">
                                            <div class="form-group row">
                                                @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-4">
                                                    <label><strong>@lang('menu.business_location') :</strong></label>
                                                    <select name="branch_id" class="form-control submit_able" id="branch_id">
                                                        <option value="">@lang('menu.all')</option>
                                                        <option value="NULL"> {{ json_decode($generalSettings->business, true)['shop_name'] }} </option>
                                                        @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}">
                                                            {{ $branch->name . '/' . $branch->branch_code }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Allow Login</th>
                                                <th>@lang('menu.name')</th>
                                                <th>@lang('menu.phone')</th>
                                                @if($addons->branches == 1)
                                                    <th>@lang('menu.business_location')</th>
                                                @endif
                                                <th>Role</th>
                                                <th>@lang('menu.email')</th>
                                                <th>@lang('menu.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
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
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    // Show session message by toster alert.
    @if(Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',className: '',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'excel',className: '',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',className: '',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        "processing": true
        , "serverSide": true,
        // aaSorting: [[8, 'asc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , "ajax": {
            "url": "{{ route('users.index') }}"
            , "data": function(d) {
                d.branch_id = $('#branch_id').val();
            }
        }
        , columns: [
            {data: 'username', name: 'username'}
            ,{data: 'allow_login', name: 'username'}
            , { data: 'name', name: 'name'}
            , { data: 'phone', name: 'phone'}
            @if($addons->branches == 1)
                , {data: 'branch', name: 'branches.name'}
            @endif
            , { data: 'role_name', name: 'role_name'}
            , {data: 'email', name: 'email'}
            , {data: 'action'}
        , ]
    , });
    // table.buttons().container().appendTo('#exportButtonsContainer');
    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function() {
        table.ajax.reload();
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation'
            , 'content': 'Are you sure?'
            , 'buttons': {
                'Yes': {
                    'class': 'yes btn-danger'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no btn-modal-primary'
                    , 'action': function() {
                        console.log('Deleted canceled.');
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
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                table.ajax.reload();
                toastr.error(data);
            }
            , error: function(error) {
                toastr.error(error.responseJSON.message);
            }
        });
    });

</script>
@endpush
