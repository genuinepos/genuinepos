@extends('layout.master')
@push('stylesheets')
<link href="{{ asset('public') }}/assets/css/tab.min.css" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'Assets - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-glass-whiskey"></span>
                                <h5>Payment Settings</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                            </a>
                        </div>

                        <div class="row px-3 mt-1">
                            <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                            <div class="card">
                                <div class="card-body">
                                    <!--begin: Datatable-->
                                    <div class="tab_list_area">
                                        <ul class="list-unstyled">
                                            <li>
                                                <a id="tab_btn" data-show="card_types" class="tab_btn tab_active" href="#"><i class="fas fa-scroll"></i> Card Types</a>
                                            </li>

                                            <li>
                                                <a id="tab_btn" data-show="comming_up" class="tab_btn" href="#"><i class="fas fa-info-circle"></i> Comming Up...</a>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="tab_contant card_types">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="btn_30_blue float-end">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#addCardTypeModal">
                                                        <i class="fas fa-plus-square"></i> Add Card Type</a>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive" >
                                                    <table class="display data_tbl data__table card_type_table">
                                                        <thead>
                                                            <tr>
                                                                <th>S/L</th>
                                                                <th>Card Type Name</th>
                                                                <th>Default Payment Account</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <form id="deleted_card_type_form" action="" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Asset Type Modal -->
    <div class="modal fade" id="addCardTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Card Type</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_card_type_form" action="{{ route('settings.payment.card.types.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>Type Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="card_type_name" class="form-control" id="card_type_name"
                                    placeholder="Card Type name" />
                                <span class="error error_card_type_name"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>Default Payment Account :</b> </label>
                                <select name="account_id" id="account_id" class="form-control">
                                    <option value="">Select Default Payment Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name.' (A/C:'.$account->account_number.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i
                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Asset Type Modal -->

    <!-- Edit Asset Type Modal -->
    <div class="modal fade" id="editCardTypeModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Card Type</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="edit_card_type_modal_body">
                    <!--begin::Form-->

                </div>
            </div>
        </div>
    </div>
    <!-- Edit Asset Type Modal -->
@endsection
@push('scripts')
<script>
    var card_types_table = $('.card_type_table').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'Payment Card types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'Payment Card types', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>Payment Card types</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('settings.payment.card.types.index') }}",
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'card_type_name',name: 'asset_type_name'},
            {data: 'account',name: 'account'},
            {data: 'action',name: 'action'},
        ],
    });

    $(document).on('submit', '#add_card_type_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $('.submit_button').prop('type', 'button');
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('#add_card_type_form')[0].reset();
                $('.loading_button').hide();
                $('#addCardTypeModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');
                card_types_table.ajax.reload();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_' + key + '').html(error[0]);
                });
                $('.submit_button').prop('type', 'submit');
            }
        });
    });

    // pass editable data to edit modal fields
    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('#edit_card_type_modal_body').html(data);
                $('#editCardTypeModal').modal('show');
            }
        });
    });

    $(document).on('submit', '#edit_card_type_form', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {
                toastr.success(data);
                $('.loading_button').hide();
                $('#editCardTypeModal').modal('hide');
                $('.error').html('');
                card_types_table.ajax.reload();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });

    $(document).on('click', '#delete_card_type',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_card_type_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-danger',
                    'action': function() {
                        $('#deleted_card_type_form').submit();
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
    $(document).on('submit', '#deleted_card_type_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            async: false,
            data: request,
            success: function(data) {
                toastr.error(data);
                card_types_table.ajax.reload();
                $('#deleted_card_type_form')[0].reset();
            }
        });
    });
</script>

@endpush
