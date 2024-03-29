@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Designations - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Designations') }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>

        <div class="p-1">
            <div class="form_element rounded m-0">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __('List Of Designations') }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('hrm.designations.create') }}" class="btn btn-sm btn-primary" id="addDesignation"><i class="fas fa-plus-square"></i> {{ __("Add Designation") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6></div>
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __("Serial") }}</th>
                                    <th>{{ __("Name") }}</th>
                                    <th>{{ __("Description") }}</th>
                                    <th>{{ __("Action") }}</th>
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

    <div class="modal fade" id="designationAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
<script>
     var designationsTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'List Of Shifts', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'List Of Shifts', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>List Of Shifts</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]
        ],
        ajax: "{{ route('hrm.designations.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'description',name: 'description'},
            {data: 'action'},
        ],
    });

     // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // call jquery method
    $(document).ready(function(){
        // Add category by ajax
        $(document).on('click', '#addDesignation', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#designationAddOrEditModal').html(data);
                    $('#designationAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#designation_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#designationAddOrEditModal').empty();
                    $('#designationAddOrEditModal').html(data);
                    $('#designationAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#designation_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                        return;
                    } else if (err.status == 500) {

                        toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                        return;
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
                'message': 'Are you sure?',
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

        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $('#data_preloader').show();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('#data_preloader').hide();
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    designationsTable.ajax.reload();
                    $('#deleted_form')[0].reset();
                },error: function(err) {

                    if (err.status == 0) {

                        toastr.error("{{ __('Net Connetion Error.') }}");
                    }else if(err.status == 500){

                        toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                    }
                }
            });
        });
    });
</script>
@endpush
