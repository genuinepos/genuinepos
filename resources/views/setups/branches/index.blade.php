@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Shop List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-code-branch"></span>
                    <h5>{{ __("Shops") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
                </a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>{{ __("Shop List") }}</h6>
                    </div>

                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                        <div class="col-md-6 d-flex justify-content-end">
                            <a id="addBtn" href="{{ route('branches.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus-square"></i> {{ __("Add New Shop") }}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="widget_content">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                    </div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Shop Name') }}</th>
                                    <th>{{ __("Shop Id") }}</th>
                                    <th>{{ __("Parent Shop") }}</th>
                                    <th>{{ __("Phone") }}</th>
                                    <th>{{ __("Address") }}</th>
                                    <th>{{ __("Shop Logo") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

    <div class="modal fade" id="branchAddOrEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
    <div class="modal fade" id="branchSettingEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>

    var branchTable = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel',className: 'btn btn-primary', exportOptions: { columns: 'th:not(:last-child)' }},
            { extend: 'pdf', text: 'Pdf', className: 'btn btn-primary', exportOptions: { columns: 'th:not(:last-child)' }},
            { extend: 'print', text: 'Print', className: 'btn btn-primary', exportOptions: { columns: 'th:not(:last-child)' }},
        ],
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        ajax: "{{ route('branches.index') }}",
        columns: [
            { data: 'branchName', name: 'branches.name' },
            { data: 'branch_code', name: 'branches.branch_code' },
            { data: 'parent_branch_name', name: 'parentBranch.name', className: 'fw-bold'},
            { data: 'phone', name: 'branches.phone' },
            { data: 'address', name: 'branches.city'},
            { data: 'logo', name: 'branches.state'},
            { data: 'action' },
        ],
    });

    // insert branch by ajax
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}} );

    // call jquery method
    $(document).ready(function(){

        $(document).on('click', '#addBtn', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#branchAddOrEditModal').html(data);
                    $('#branchAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#branch_type').focus();
                    }, 500);
                }, error:function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#branchAddOrEditModal').html(data);
                    $('#branchAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#branch_type').focus();
                    }, 500);
                }, error:function(err){

                    if (err.status == 0) {

                        toastr.error('{{ __("Net Connetion Error.") }}');
                    } else {

                        toastr.error('{{ __("Server Error. Please contact to the support team.") }}');
                    }
                }
            });
        });

        $(document).on('click', '#branchSettings', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#branchSettingEditModal').html(data);
                    $('#branchSettingEditModal').modal('show');

                    setTimeout(function() {

                        $('#branch_setting_invoice_prefix').focus().select();
                    }, 500);
                }, error:function(err){

                    if (err.status == 0) {

                        toastr.error('{{ __("Net Connetion Error.") }}');
                    } else {

                        toastr.error('{{ __("Server Error. Please contact to the support team.") }}');
                    }
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}
                    },
                    'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.');}}
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
                type:'delete',
                data:request,
                success:function(data){

                    toastr.error(data);
                }
            });
        });
    });
</script>
@endpush
