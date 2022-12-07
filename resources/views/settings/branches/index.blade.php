@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Business Location List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-code-branch"></span>
                    <h5>@lang('menu.business_location')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i>@lang('menu.back')
                </a>
            </div>
        </div>

        <div class="p-3">
            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>@lang('menu.all_business_locations')</h6>
                    </div>

                    @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                        <div class="col-md-6 d-flex justify-content-end">
                            <a id="create" href="{{ route('settings.branches.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus-square"></i> Add Business Location
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
                                    <th class="text-white">Logo</th>
                                    <th class="text-white">B.Location Name</th>
                                    <th class="text-white">Branch Code</th>
                                    <th class="text-white">@lang('menu.phone')</th>
                                    <th class="text-white">@lang('menu.city')</th>
                                    <th class="text-white">@lang('menu.state')</th>
                                    <th class="text-white">@lang('menu.zip_code')</th>
                                    <th class="text-white">@lang('menu.country')</th>
                                    <th class="text-white">@lang('menu.email')</th>
                                    <th class="text-white">Actions</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Business Location</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_branch_modal_body"></div>
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
                <div class="modal-body" id="quick_schema_add_modal_body"></div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Edit Business Location</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>

                <div class="modal-body" id="edit-modal-body"></div>
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
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}} );

    // call jquery method
    $(document).ready(function(){

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

        $(document).on('click', '#create', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#add_branch_modal_body').html(data);
                    $('#addBranchModal').modal('show');
                    $('.data_preloader').hide();
                },error:function(err){
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#add_inv_schema', function(e) {
            e.preventDefault();
            console.log('Clicked');
            $('.data_preloader').show();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#quick_schema_add_modal_body').html(data);
                    $('#quickInvSchemaModal').modal('show');
                    $('.data_preloader').hide();
                },error:function(err){
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
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
                    getAllBranch();
                    toastr.error(data);
                }
            });
        });
    });
</script>
@endpush
