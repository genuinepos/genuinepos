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
                                <span class="fas fa-warehouse"></span>
                                <h5>Warehouses</h5>
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
                                                        <div class="col-md-3">
                                                            <label><strong>Branch :</strong></label>
                                                            <select name="branch_id"
                                                                class="form-control submit_able" id="branch_id" autofocus>
                                                                <option value="">All</option>
                                                                <option value="NULL">{{ json_decode($generalSettings->business, true)['shop_name'] }} (Head Office)</option>
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
                        <div class="col-md-4">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>Add Warehouse </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2">
                                    <form id="add_warehouse_form" action="{{ route('settings.warehouses.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label><b>Warehouse Name :</b>  <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control add_input" data-name="Warehouse name" id="name" placeholder="Warehouse name"/>
                                            <span class="error error_name"></span>
                                        </div>
                
                                        <div class="form-group mt-1">
                                            <label><b>Warehouse Code :</b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Warehouse code must be unique." class="fas fa-info-circle tp"></i></label>
                                            <input type="text" name="code" class="form-control add_input" data-name="Warehouse code" id="code" placeholder="Warehouse code"/>
                                            <span class="error error_code"></span>
                                        </div>
                
                                        <div class="form-group mt-1">
                                            <label><b>Phone :</b>  <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
                                            <span class="error error_phone"></span>
                                        </div>
                
                                        <div class="form-group mt-1">
                                            <label><b>Address :</b>  </label>
                                            <textarea name="address" class="form-control" placeholder="Warehouse address" rows="3"></textarea>
                                        </div>
                
                                        <div class="form-group text-end mt-3">
                                            <button type="button" class="btn loading_button d-none"><i
                                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                            <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
                                            <button type="reset" class="c-btn btn_orange float-end">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card d-none" id="edit_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>Edit Warehouse </h6>
                                    </div>
                                </div>

                                <div class="form-area px-3 pb-2" id="edit_form_body">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-6">
                                        <h6>All Warehouse</h6>
                                    </div>
                                </div>
        
                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">SL</th>
                                                    <th class="text-start">Name</th>
                                                    <th class="text-start">Branch</th>
                                                    <th class="text-start">Warehouse Code</th>
                                                    <th class="text-start">Phone</th>
                                                    <th class="text-start">Address</th>
                                                    <th class="text-start">Actions</th>
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
    var table = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [ 
            //{extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        aaSorting: [[2, 'desc']],
        "lengthMenu" : [25, 100, 500, 1000, 2000]
        "ajax": {
            "url": "{{ route('settings.warehouses.index') }}",
            "data": function(d) {d.branch_id = $('#branch_id').val();}
        },
        columnDefs: [{"targets": [6],"orderable": false,"searchable": false}],
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'branch',name: 'branch'},
            {data: 'code',name: 'code'},
            {data: 'phone',name: 'phone'},
            {data: 'address',name: 'address'},
            {data: 'action',name: 'action'},
        ],
    });

    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
    });

    // Setup CSRF Token for ajax request
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method 
    $(document).ready(function(){
        // Add Warehouse by ajax
        $('#add_warehouse_form').on('submit', function(e){
            e.preventDefault();
             $('.loading_button').show();
             $('.submit_button').prop('type', 'button');
            var url = $(this).attr('action');
            var request = $(this).serialize();
            var inputs = $('.add_input');
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
                data:request,
                success:function(data){
                    toastr.success(data);
                    $('#add_warehouse_form')[0].reset();
                    $('.loading_button').hide();
                    table.ajax.reload();
                    $('.submit_button').prop('type', 'submit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit_form_body').html(data);
                    $('#add_form').hide();
                    $('#edit_form').show();
                    $('.data_preloader').hide();
                }
            });
        });

        //Edit warehouse by ajax
        $(document).on('submit', '#edit_warehouse_form', function(e){
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
                data:request,
                success:function(data){
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
            var id = $(this).data('id');
            $('#deleted_form').attr('action', url);
            $('#deleteId').val(id);      
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {'class': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},
                    'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}
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
                    if($.isEmptyObject(data.errorMsg)){
                        toastr.error(data);
                        table.ajax.reload();
                    }else{toastr.error(data.errorMsg, 'Error');}
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
