@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Brand - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class=" border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-band-aid"></span>
                                <h5>Brands</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="row mt-1">
                        @if (auth()->user()->permission->brand['brand_add'] == '1')
                            <div class="col-md-4">
                                <div class="card" id="add_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Add Brand </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2">
                                        <form id="add_brand_form" action="{{ route('product.brands.store') }}">
                                            <div class="form-group">
                                                <label><b>@lang('brand.name') :</b> <span class="text-danger">*</span></label> 
                                                <input type="text" name="name" class="form-control  add_input" data-name="Brand name" id="name"
                                                    placeholder="Brand Name" />
                                                <span class="error error_name"></span>
                                            </div>
                    
                                            <div class="form-group mt-1">
                                                <label><b>@lang('brand.brand_photo') :</b></label> 
                                                <input type="file" name="photo" class="form-control" data-max-file-size="2M" id="photo"
                                                    accept=".jpg, .jpeg, .png, .gif">
                                                <span class="error error_photo"></span>
                                            </div>
                    
                                            <div class="form-group mt-2">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                    <button type="submit" class="c-btn btn_blue float-end submit_button me-0">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="card d-none" id="edit_form">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <h6>Edit Brand </h6>
                                        </div>
                                    </div>

                                    <div class="form-area px-3 pb-2" id="edit_form_body">

                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Brand</h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Serial</th>
                                                    <th>Photo</th>
                                                    <th>Name</th>
                                                    <th>Actions</th>
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
        // Get all brands by ajax
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [ 
                //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            processing: true,
            serverSide: true,
            searchable: true,
            ajax: "{{ route('product.brands.index') }}",
            columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
                {data: 'photo',name: 'photo'},
                {data: 'name',name: 'name'},
                {data: 'action',name: 'action'},
            ]
        });

        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // call jquery method 
        $(document).ready(function() {
            // Add brand by ajax
            $('#add_brand_form').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').removeClass('d-none');
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.add_input');
                $('.error').html('');
                var countErrorField = 0;
                $.each(inputs, function(key, val) {
                    var inputId = $(val).attr('id');
                    var idValue = $('#' + inputId).val()
                    if (inputId !== 'parent_category' && inputId !== 'photo') {
                        if (idValue == '') {
                            countErrorField += 1;
                            var fieldName = $('#' + inputId).data('name');
                            $('.error_' + inputId).html(fieldName +' is required.');
                        }
                    }
                });

                if (countErrorField > 0) {
                    $('.loading_button').hide();
                    return;
                }

                $('.submit_button').prop('type', 'button');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#add_brand_form')[0].reset();
                        $('.loading_button').hide();
                        $('.submit_button').prop('type', 'submit');
                        table.ajax.reload();
                    }
                });
            });

            // pass editable data to edit modal fields
            $(document).on('click', '.edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var id = $(this).data('id');
                $.get("brands/edit/" + id, function(data) {
                    $("#edit_form_body").html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                })
            });

            // edit brand by ajax
            $(document).on('submit', '#edit_brand_form', function(e){
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                var inputs = $('.edit_input');
                    $('.error').html('');  
                    var countErrorField = 0;  
                $.each(inputs, function(key, val){
                    var inputId = $(val).attr('id');
                    var idValue = $('#'+inputId).val()
                    if(idValue == ''){
                        countErrorField += 1;
                        var fieldName = $('#'+inputId).data('name');
                        $('.error_'+inputId).html(fieldName+' is required.');
                    } 
                });
                if(countErrorField > 0){
                    $('.loading_button').hide();
                    return;
                }
                $.ajax({
                    url:url,
                    type:'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data){
                        $('.error').html('');
                        toastr.success(data);
                        $('.loading_button').hide();
                        table.ajax.reload();
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
                    'title': '@lang("brand.delete_alert")',
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
                    async: false,
                    data: request,
                    success: function(data) {
                        $('.data_tbl').DataTable().ajax.reload();
                        toastr.error(data);
                    }
                });
            });

            $(document).on('click', '#close_form', function() {
                $('#add_form').show();
                $('#edit_form').hide();
            });
        });
    </script>
@endpush
