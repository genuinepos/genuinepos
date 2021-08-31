@extends('layout.master')
@push('stylesheets') @endpush
@section('title', 'User List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-user"></span>
                                <h5>Users</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        @if ($addons->branches == 1)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="sec-name">
                                        <div class="col-md-12">
                                            <form action="" method="get" class="px-2">
                                                <div class="form-group row">
                                                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                        <div class="col-md-4">
                                                            <label><strong>Business Location :</strong></label>
                                                            <select name="branch_id" class="form-control submit_able" id="branch_id">
                                                                <option value="">All</option>
                                                                <option value="NULL"> {{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
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
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>All User </h6>
                                </div>
                                @if (auth()->user()->permission->user['user_add'] == '1')
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href="{{ route('users.create') }}"><i
                                                    class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget_content">
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Username</th>
                                                <th class="text-start">Name</th>
                                                <th class="text-start">Branch</th>
                                                <th class="text-start">Role</th>
                                                <th class="text-start">Department</th>
                                                <th class="text-start">Designation</th>
                                                <th class="text-start">Email</th>
                                                <th class="text-start">Salary</th>
                                                <th class="text-start">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
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
        </div>
    </div>
@endsection
@push('scripts')
  
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            aaSorting: [[3, 'asc']],
            "ajax": {
                "url": "{{ route('users.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
         
            columns: [
                {data: 'username', name: 'username'},
                {data: 'name', name: 'name'},
                {data: 'branch', name: 'branch'},
                {data: 'role', name: 'role'},
                {data: 'dep_name', name: 'dep_name'},
                {data: 'des_name', name: 'des_name'},
                {data: 'email', name: 'email'},
                {data: 'salary', name: 'salary'},
                {data: 'action'},
            ],
        });

        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function () {
            table.ajax.reload();
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault(); 
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);       
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
                        'action': function() {
                            // alert('Deleted canceled.')
                        } 
                    }
                }
            });
        });
            
        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });
    </script>
@endpush