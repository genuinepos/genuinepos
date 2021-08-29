@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'HRM Leaves - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="breadCrumbHolder module w-100">
                                <div id="breadCrumb3" class="breadCrumb module">
                                    <ul >
                                        <li>
                                            <a href="{{ route('manufacturing.process.index') }}" class="text-white"><i class="fas fa-dumpster-fire"></i> <b>Process</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-shapes"></i> <b>Production</b></a>
                                        </li>
                                     
                                        <li>
                                            <a href="{{ route('manufacturing.settings.index') }}" class="text-white"><i class="fas fa-sliders-h text-primary"></i> <b>Settings</b></a>
                                        </li>

                                        <li>
                                            <a href="" class="text-white"><i class="fas fa-file-alt"></i> <b>Manufacturing Report</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>Settings</h6>
                                </div>
                            </div>

                            <form id="update_settings_form" action="{{ route('manufacturing.settings.store') }}" method="post" class="p-3">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label><strong>Production Reference prefix :</strong></label>
                                        <input type="text" name="production_ref_prefix" class="form-control"
                                            autocomplete="off" placeholder="Production Reference prefix"
                                            value="{{ json_decode($generalSettings->mf_settings, true)['production_ref_prefix'] }}">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="row mt-1">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->mf_settings, true)['disable_editing_ingredient_qty'] == '1' ? 'CHECKED' : '' }}
                                                    name="disable_editing_ingredient_qty"> &nbsp; <b>Disable editing ingredients quantity in production</b> 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="row mt-1">
                                            <p class="checkbox_input_wrap mt-4">
                                                <input type="checkbox"
                                                    {{ json_decode($generalSettings->mf_settings, true)['enable_updating_product_price'] == '1' ? 'CHECKED' : '' }}
                                                    name="enable_updating_product_price"> &nbsp; <b>Update product purchase price based on production price, on finalizing production</b> 
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn loading_button d-none"><i
                                            class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
                                        <button class="btn btn-sm btn-primary submit_button float-end">Save Change</button>
                                    </div>
                                </div>
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
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method 
    $(document).ready(function(){
        // Update settings by ajax
        $('#update_settings_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    });
</script>
@endpush
