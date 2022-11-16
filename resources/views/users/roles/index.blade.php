@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Role List - ')
@section('content')
    <div class="body-wraper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>Roles</h6>
                            </div>
                            <div class="d-flex">
                                @if (auth()->user()->can('role_add'))
                                    <div>
                                        <a href="{{ route('users.role.create') }}" class="btn text-white btn-sm"><i class="fa-thin fa-circle-plus fa-2x"></i><br>New Role</a>
                                    </div>
                                @endif
                                <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                            </div>
                            <div>
                                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i    class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-15">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
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
@endsection
@push('scripts')
<script>
    @if (Session::has('successMsg'))
        toastr.success('{{ session('successMsg') }}');
    @endif

    function getAllRoles(){
        $('.data_preloader').show();
        $.ajax({
            url:"{{ route('users.role.all.roles') }}",
            type:'get',
            success:function(data){
                $('.table-responsive').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getAllRoles();

    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
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
                type:'post',
                data:request,
                success:function(data){
                    getAllRoles();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                },
                error: function(data){
                    toastr.error(data.responseJSON.message);
                }
            });
        });
    });
</script>
@endpush
