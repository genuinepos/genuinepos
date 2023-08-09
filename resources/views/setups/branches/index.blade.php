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

        <div class="p-3">
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
                                    <th>{{ __("Shop Logo") }}</th>
                                    <th>{{ __('Shop Name') }}</th>
                                    <th>{{ __("Shop Id") }}</th>
                                    <th>{{ __("Parent Shop") }}</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="branchAddOrEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>

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

                        $('#shop_type').focus();
                    }, 500);
                }, error:function(err){

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error.');
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

                    toastr.error(data);
                }
            });
        });
    });
</script>
@endpush
