@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
        .form-control {padding: 4px!important;}
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/backend/asset/css/select2.min.css"/>
@endpush
@section('title', 'HRM Payrolls - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul class="list-unstyled">
                                        @if (auth()->user()->permission->essential['assign_todo'] == '1')
                                            <li>
                                                <a href="{{ route('workspace.index') }}" class="text-white"><i class="fas fa-th-large"></i> <b>Work Spaces</b></a>
                                            </li>
                                        @endif

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-th-large"></i> <b>Document</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-th-large"></i> <b>Memos</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-th-large"></i> <b>Remainders</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-th-large"></i> <b>Messages</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-th-large"></i> <b>Knowledge Base</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="sec-name">
                                    <div class="col-md-12">
                                        <i class="fas fa-funnel-dollar ms-2"></i> <b>Filter</b>
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

                                                <div class="col-md-3">
                                                    <label><strong>Priority : </strong></label>
                                                    <select name="priority"
                                                        class="form-control submit_able" id="priority" autofocus>
                                                        <option value="">All</option>
                                                        <option value="Low">Low</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="High">High</option>
                                                        <option value="Urgent">Urgent</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Status : </strong></label>
                                                    <select name="status"
                                                        class="form-control submit_able" id="status" autofocus>
                                                        <option value="">All</option>
                                                        <option value="New">New</option>
                                                        <option value="In-Progress">In-Progress</option>
                                                        <option value="On-Hold">On-Hold</option>
                                                        <option value="Complated">Complated</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label><strong>Date Range :</strong></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-calendar-week input_i"></i></span>
                                                        </div>
                                                        <input readonly type="text" name="date_range" id="date_range"
                                                            class="form-control daterange submit_able_input"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->

                    <div class="row">
                        <div class="form_element">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>All Work Space <i data-bs-toggle="tooltip" data-bs-placement="right" title="Note: Initially current year's data is available here, if need another year's data go to the data filter." class="fas fa-info-circle tp"></i></h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="btn_30_blue float-end">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                                class="fas fa-plus-square"></i> Add</a>
                                    </div>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>Entry Date</th>
                                                <th>Work Space ID</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Estemated Hour</th>
                                                <th>Assigned By</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-55-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Work Space</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form  action="" method="get">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><b>Name :</b></label>
                                <input required type="text" name="name" class="form-control" placeholder="Workspace Name">
                            </div>

                            <div class="col-md-6">
                                <label><b>Assigned To :</b></label>
                                <select required name="user_ids" class="form-control select2" id="user_ids" multiple="multiple">
                                    <option> Select Please </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>Priority : </b></label>
                                <select name="priority"
                                    class="form-control" id="priority" autofocus>
                                    <option value="">All</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                    <option value="Urgent">Urgent</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label><strong>Status : </strong></label>
                                <select name="status" class="form-control" id="status" autofocus>
                                    <option value="">All</option>
                                    <option value="New">New</option>
                                    <option value="In-Progress">In-Progress</option>
                                    <option value="On-Hold">On-Hold</option>
                                    <option value="Complated">Complated</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><b>Description : </b></label>
                                <textarea name="description" class="form-control" id="description" cols="10" rows="3" placeholder="Workspace Description."></textarea>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-md-6">
                                <label><b>Documents : </b></label>
                                <input type="file" name="documents[]" class="form-control" multiple id="documents" placeholder="Workspace Description.">
                            </div>

                            <div class="col-md-6">
                                <label><b>Estimated Hours : </b></label>
                                <input type="text" name="estimated_hours" class="form-control" id="estimated_hours" placeholder="Estimated Hours">
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <div class="col-md-12">
                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="c-btn btn_orange float-end">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal End-->
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('public') }}/assets/plugins/custom/moment/moment.min.js"></script>
<script src="{{ asset('public') }}/assets/plugins/custom/daterangepicker/daterangepicker.js"></script>
<script src="{{ asset('public') }}/backend/asset/js/select2.min.js"></script>
<script>
    //Submit filter form by select input changing
    $(document).on('change', '.submit_able', function () {
        table.ajax.reload();
    });

    //Submit filter form by date-range field blur 
    $(document).on('blur', '.submit_able_input', function () {
        setTimeout(function() {
            table.ajax.reload();
        }, 800);
    });

    //Submit filter form by date-range apply button
    $(document).on('click', '.applyBtn', function () {
        setTimeout(function() {
            $('.submit_able_input').addClass('.form-control:focus');
            $('.submit_able_input').blur();
        }, 1000);
    });


    // //Show payment view modal with data
    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(date){
               
            }
        });
    });

    // Show add payment modal with date 
    $(document).on('click', '#edit', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        $.ajax({
            url:url,
            type:'get',
            success:function(data){
      
            }
        });
    });

    //Add sale payment request by ajax
    $(document).on('submit', '#add_work_space_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var inputs = $('.p_input');
            $('.error').html('');  
            var countErrorField = 0;  
        $.each(inputs, function(key, val){
            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();
            if(idValue == ''){
                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){
            $('.loading_button').hide();
            toastr.error('Please check again all form fields.','Some thing want wrong.'); 
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
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else{
                    table.ajax.reload();
                    $('.loading_button').hide();
                    $('.modal').modal('hide');
                    toastr.success(data); 
                }
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

<script type="text/javascript">
    $(function() {
        var start = moment().startOf('year');
        var end = moment().endOf('year');
        $('.daterange').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')],
            }
        });
    });
    $('.select2').select2();
</script>

@endpush
