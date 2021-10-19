@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Branch List - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-code-branch"></span>
                                <h5>Business Locations</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="row px-3 mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Business Locations</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                class="fas fa-plus-square"></i> Add</a>
                                    </div>
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
                                                <th class="text-white">Logo</th>
                                                <th class="text-white">B.Location Name</th>
                                                <th class="text-white">Branch Code</th>
                                                <th class="text-white">Phone</th>
                                                <th class="text-white">City</th>
                                                <th class="text-white">State</th>
                                                <th class="text-white">Zip-Code</th>
                                                <th class="text-white">Country</th>
                                                <th class="text-white">Email</th>
                                                <th class="text-white">Actions</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Business Location</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_branch_form" action="{{ route('settings.branches.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label><strong>Name :</strong>  <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control add_input" data-name="Name" id="name" placeholder="Business Location Name"/>
                                <span class="error error_name"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Location Code :</strong>  <span class="text-danger">*</span> <i data-bs-toggle="tooltip" data-bs-placement="top" title="Branch code must be unique." class="fas fa-info-circle tp"></i></label>
                                <input type="text" name="code" class="form-control  add_input" data-name="Branch code" id="code" placeholder="Business Location Code"/>
                                <span class="error error_code"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Phone :</strong>  <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control  add_input" data-name="Phone number" id="phone" placeholder="Phone number"/>
                                <span class="error error_phone"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Alternate Phone Number :</strong> </label>
                                <input type="text" name="alternate_phone_number" class="form-control " id="alternate_phone_number" placeholder="Alternate phone number"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label> <strong>City :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="city" class="form-control  add_input" data-name="City" id="city" placeholder="City"/>
                                <span class="error error_city"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>State :</strong>  <span class="text-danger">*</span></label>
                                <input type="text" name="state" class="form-control  add_input" data-name="State" id="state" placeholder="State name"/>
                                <span class="error error_state"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Country :</strong>  <span class="text-danger">*</span></label>
                                <input type="text" name="country" class="form-control  add_input" data-name="country" id="country" placeholder="Country"/>
                                <span class="error error_country"></span>
                            </div>

                            <div class="col-md-3">
                                <label> <strong>Zip-code :</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="zip_code" class="form-control  add_input" data-name="Zip code" id="zip_code" placeholder="Zip code"/>
                                <span class="error error_zip_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label> <strong>Email :</strong> </label>
                                <input type="text" name="email" class="form-control "  id="email" placeholder="Email address"/>
                            </div>

                            <div class="col-md-3">
                                <label> <strong>Website :</strong> </label>
                                <input type="text" name="website" class="form-control " id="website" placeholder="Website URL"/>
                            </div>

                            <div class="col-md-3">
                                <label> <strong>Branch Logo :</strong> <small class="text-danger">Logo size 200px * 70px</small> </label>
                                <input type="file" name="logo" class="form-control " id="logo"/>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-3">
                                <label><strong>Default Account :</strong> </label>
                                <select name="default_account_id" id="default_account_id" class="form-control ">
                                    <option value="">Select Please</option>
                                    @foreach ($accounts as $ac)
                                        <option value="{{ $ac->id }}">{{ $ac->name.' (A/C:'.$ac->account_number.')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Add Sale Invoice Scheme :</strong>  <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="invoice_schema_id" id="invoice_schema_id" data-name="invoice schema" class="form-control add_input">
                                        <option value="">Select Please</option>
                                        @foreach ($invSchemas as $schema)
                                            <option value="{{ $schema->id }}">{{ $schema->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text add_button" data-bs-toggle="modal"
                                            data-bs-target="#quickInvSchemaModal"><i class="fas fa-plus-square input_i"></i></span>
                                    </div>
                                </div>
                                <span class="error error_invoice_schema_id"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>Add Sale Invoice Layout :</strong>  <span class="text-danger">*</span></label>
                                <select name="add_sale_invoice_layout_id" id="add_sale_invoice_layout_id" data-name="Add sale invoice layout" class="form-control  add_input">
                                    <option value="">Select Please</option>
                                    @foreach ($invLayouts as $layout)
                                        <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_add_sale_invoice_layout_id"></span>
                            </div>

                            <div class="col-md-3">
                                <label><strong>POS Sale Invoice Layout :</strong>  <span class="text-danger">*</span></label>
                                <select name="pos_sale_invoice_layout_id" id="pos_sale_invoice_layout_id" data-name="POS sale invoice layout" class="form-control  add_input">
                                    <option value="">Select Please</option>
                                    @foreach ($invLayouts as $layout)
                                        <option value="{{ $layout->id }}">{{ $layout->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_pos_sale_invoice_layout_id"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-4">
                                <div class="row">
                                    <p class="checkbox_input_wrap mt-2">
                                <input type="checkbox" name="purchase_permission" id="purchase_permission" value="1"> &nbsp; <b>Enable purchase permission</b></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-end">
                            <button type="button" class="btn loading_button d-none"><i
                                class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="me-0 c-btn btn_blue float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end submit_button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="quickInvSchemaModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Invoice Schema</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_schema_form" action="{{ route('settings.branches.quick.invoice.schema') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><b>Preview : <span id="q_schema_preview"></span></label>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>Name :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="q_name" placeholder="Schema name"/>
                                <span class="error error_q_name"></span>
                            </div>

                            <div class="col-md-6">
                                <label><b>Format :</b> <span class="text-danger">*</span></label>
                                <select name="format" class="form-control" id="q_format">
                                    <option value="1">FORMAT-XXXX</option>
                                    <option value="2">FORMAT-{{ date('Y') }}/XXXX</option>
                                </select>
                                <span class="error error_q_format"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>Prefix :</b> <span class="text-danger">*</span></label>
                                <input type="text" name="prefix" class="form-control" id="q_prefix" placeholder="Prefix"/>
                                <span class="error error_q_prefix"></span>
                            </div>

                            <div class="col-md-6">
                                <label><b>Start From :</b></label>
                                <input type="number" name="start_from" class="form-control" id="q_start_from" placeholder="Start From" value="0"/>
                            </div>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="button" class="btn loading_button d-none q_ld_btn"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                            <button type="submit" class="c-btn btn_blue me-0 float-end">Save</button>
                            <button type="reset" data-bs-dismiss="modal" class="c-btn btn_orange float-end">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Business Location</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="edit-modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
@endsection
@push('scripts')
<script>
    // Get all branch by ajax
    function getAllBranch(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('settings.get.all.branch') }}",
            type:'get',
            success:function(data){
                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllBranch();

    // insert branch by ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function(){
        // Add branch by ajax
        $('#add_branch_form').on('submit', function(e){
            e.preventDefault();
             $('.loading_button').show();
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

            $('.submit_button').prop('type', 'button');
            $.ajax({
                url:url,
                type:'post',
                data:new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    toastr.success(data);
                    $('#add_branch_form')[0].reset();
                    $('.loading_button').hide();
                    getAllBranch();
                    $('#addModal').modal('hide');
                    $('.submit_button').prop('type', 'sumbit');
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#edit-modal-body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            });
        });

        // edit branch by ajax
        $(document).on('submit', '#edit_branch_form',function(e){
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
                data:new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success:function(data){
                    $('#editModal').modal('hide');
                    toastr.success(data);
                    $('.loading_button').hide();
                    getAllBranch();
                }
            });
        });

        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
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
                    getAllBranch();
                    toastr.error(data.errorMsg, 'Error');
                }
            });
        });

        $(document).on('change', '#q_format', function () {
            var val = $(this).val();
            if (val == 2) {
                $('#q_prefix').val("{{ date('Y') }}"+'/');
                $('#q_prefix').prop('readonly', true);
            }else{
                $('#q_prefix').val("");
                $('#q_prefix').prop('readonly', false);
            }
            previewInvoieId();
        });

        $(document).on('input', '#q_prefix', function () {previewInvoieId();});
        $(document).on('input', '#q_start_from', function () {previewInvoieId();});

        function previewInvoieId() {
            var prefix = $('#q_prefix').val();
            var start_from = $('#q_start_from').val();
            $('#q_schema_preview').html('#'+prefix+start_from);
        }

        // Add category by ajax
        $(document).on('submit', '#add_schema_form',function(e){
            e.preventDefault();
            $('.q_ld_btn').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('#add_schema_form')[0].reset();
                    $('.q_ld_btn').hide();
                    $('#quickInvSchemaModal').modal('hide');
                    $('#q_schema_preview').html('');
                    $('#q_prefix').prop('readonly', false);
                    $('#invoice_schema_id').append('<option SELECTED value="'+data.id+'">'+data.name+'</option>');
                },
                error: function(err) {
                    $('.q_ld_btn').hide();
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_q_' + key + '').html(error[0]);
                    });
                }
            });
        });
    });
</script>
@endpush
