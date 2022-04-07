@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Customer Group</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>Add Customer Group</h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_group_form" action="{{ route('contacts.customers.groups.store') }}">
                                        <div class="form-group mt-2">
                                            <label><strong>Name :</strong> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control add_input"
                                                data-name="Group name" id="name" placeholder="Group name" />
                                            <span class="error error_name"></span>
                                        </div>
                
                                        <div class="form-group mt-2">
                                            <label><strong>Calculation Percent (%) :</strong></label>
                                            <input type="number" step="any" name="calculation_percent" class="form-control" step="any"
                                                id="calculation_percent" placeholder="Calculation Percent" autocomplete="off" />
                                        </div>
                
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                <button type="submit" class="c-btn button-success me-0 float-end">Save</button>
                                                <button type="reset" class="c-btn btn_orange float-end">Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card d-none" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>Edit Customer Group</h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="edit_group_form" action="{{ route('contacts.customers.groups.update') }}">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group mt-2">
                                            <label><strong>Name :</strong> <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control edit_input"
                                                data-name="Group name" id="e_name" placeholder="Group name" />
                                            <span class="error error_e_name"></span>
                                        </div>
                
                                        <div class="form-group mt-2">
                                            <label><strong>Calculation Percent (%) :</strong></label>
                                            <input type="number" step="any" name="calculation_percent" class="form-control"
                                                id="e_calculation_percent" placeholder="Calculation Percent" />
                                        </div>
                
                                        <div class="form-group row mt-3">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i
                                                        class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                <button type="submit" class="c-btn button-success me-0 float-end">Save</button>
                                                <button type="button" id="close_form" class="c-btn btn_orange float-end">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Customer Groups</h6>
                                    </div>
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Serial</th>
                                                    <th>Name</th>
                                                    <th>Calculation Percent</th>
                                                    <th>Action</th>
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
    </div>
@endsection
@push('scripts')
    <script>
        // Get all customer group by ajax
        function getAllCustomerGroup() {
            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('contacts.customers.groups.all.group') }}",
                type: 'get',
                success: function(data) {
                    $('.table-responsive').html(data);
                    $('.data_preloader').hide();
                }
            });
        }
        getAllCustomerGroup();

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        $(document).ready(function() {
            // Add Customer Group by ajax
            $('#add_group_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();

                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }

                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_group_form')[0].reset();
                        $('.loading_button').hide();
                        getAllCustomerGroup();
                        $('#addModal').modal('hide');
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.error').html('');
                var group = $(this).closest('tr').data('info');
                $('#id').val(group.id);
                $('#e_name').val(group.group_name);
                $('#e_calculation_percent').val(group.calc_percentage);
                $('#add_form').hide();
                $('#edit_form').show();
                document.getElementById('e_name').focus();
            });

            // Edit Customer by ajax
            $('#edit_group_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val();
                    if (idValue == '') {
                        countErrorField += 1;
                        var fieldName = $('#' + inputId).data('name');
                        $('.error_' + inputId).html(fieldName + ' is required.');
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        console.log(data);
                        toastr.success(data);
                        $('.loading_button').hide();
                        getAllCustomerGroup();
                        $('#add_form').show();
                        $('#edit_form').hide();
                    }
                });
            });

            $(document).on('click', '#delete',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);           
                $.confirm({
                    'title': 'Delete Confirmation',
                    'message': 'Are you sure?',
                    'buttons': {
                        'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                        'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
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
                    async: false,
                    data: request,
                    success: function(data) {
                        getAllCustomerGroup();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                });
            });

            $(document).on('click', '#close_form', function() {
                $('#add_form').show();
                $('#edit_form').hide();
                $('.error').html('');
            });
        });
    </script>
@endpush
