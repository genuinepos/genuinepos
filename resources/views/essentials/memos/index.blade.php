@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li { display: inline-block;margin-right: 3px; }
        .top-menu-area a { border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px; }
        .form-control { padding: 4px!important; }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('public') }}/backend/asset/css/select2.min.css"/>
@endpush
@section('title', 'Essentials - ')
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
                                            <a href="" class="text-white"><i class="fas fa-file-alt text-primary"></i> <b>Memos</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-stopwatch"></i> <b>Remainders</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-envelope"></i> <b>Messages</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-snowflake"></i> <b>Knowledge Base</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="col-md-5">
                            <div class="card" id="add_form">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>Add Meno </h6>
                                    </div>
                                </div>

                                <div class="form-area px-2 pb-2">
                                    <form id="add_memo_form" action="{{ route('memos.store') }}">
                                        <div class="from-group">
                                            <label><b>Heading :</b></label>
                                            <input type="text" class="form-control" name="heading" placeholder="Memo Heading">
                                        </div>

                                        <div class="from-group mt-1">
                                            <label><b>Description :</b></label>
                                            <textarea name="description" class="form-control" id="description" cols="10" rows="4" placeholder="Memo Description"></textarea>
                                        </div>

                                        <div class="form-group row mt-2">
                                            <div class="col-md-12">
                                                <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                                <button type="submit" class="c-btn me-0 btn_blue float-end">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="card">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <h6>All Memos </h6>
                                    </div>
                                </div>
    
                                <div class="widget_content">
                                    <div class="data_preloader"> <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6></div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Created Date</th>
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
<script src="{{ asset('public') }}/backend/asset/js/select2.min.js"></script>
<script>
    // //Show payment view modal with data
    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');
        // $.ajax({
        //     url:url,
        //     type:'get',
        //     success:function(date){
            
        //     }
        // });
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
                $('#edit_modal_body').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();
            }
        });
    });

    //Add workspace request by ajax
    $(document).on('submit', '#add_memo_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else{
                    $('#add_memo_form')[0].reset();
                    $('.loading_button').hide();
                    $('.modal').modal('hide');
                    toastr.success(data); 
                    //table.ajax.reload();
                }
            }
        });
    });

    //Edit workspace request by ajax
    $(document).on('submit', '#edit_memo_form', function(e){
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){
                if(!$.isEmptyObject(data.errorMsg)){
                    toastr.error(data.errorMsg,'ERROR'); 
                    $('.loading_button').hide();
                }else{
                    $('.loading_button').hide();
                    $('.modal').modal('hide');
                    toastr.success(data); 
                    table.ajax.reload();
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

@endpush
