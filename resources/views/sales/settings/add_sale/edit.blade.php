@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
@endpush
@section('title', 'Add Sale Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __("Add Sale Settings") }}</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <form id="add_sale_settings_form" class="setting_form p-3" action="{{ route('add.sales.settings.update') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label><strong>@lang('menu.default_sale_discount') </strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-percent text-dark input_f"></i></span>
                                        </div>
                                        <input type="text" name="default_sale_discount" class="form-control" id="default_sale_discount" autocomplete="off" value="{{ $generalSettings['add_sale__default_sale_discount'] }}" data-next="sales_commission" autofocus>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __('Sales Commission') }}</strong></label>
                                    <select class="form-control" name="sales_commission" id="sales_commission" data-next="default_price_group_id">
                                        <option {{ $generalSettings['sale__sales_commission'] == 'disable' ? 'SELECTED' : '' }} value="disable">{{ __('Disable') }}
                                        </option>

                                        <option {{ $generalSettings['sale__sales_commission'] == 'enable' ? 'SELECTED' : '' }} value="enable">{{ __('Enable') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label><strong>{{ __('Default Selling Price Group') }} </strong></label>
                                    <select name="default_price_group_id" class="form-control" id="default_price_group_id" data-next="save_changes">
                                        <option value="null">@lang('menu.none')</option>
                                        @foreach ($priceGroups as $priceGroup)
                                            <option {{ $generalSettings['add_sale__default_price_group_id'] == $priceGroup->id ? 'SELECTED' : '' }} value="{{ $priceGroup->id }}">{{ $priceGroup->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button add_sale_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                                        <button type="button" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __("Save Changes") }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).on('click keypress focus blur change', '.form-control', function(event) {

         $('.submit_button').prop('type', 'button');
     });

     var isAllowSubmit = true;
     $(document).on('click', '.submit_button', function() {

         if (isAllowSubmit) {

             $(this).prop('type', 'submit');
         } else {

             $(this).prop('type', 'button');
         }
     });

     $('#add_sale_settings_form').on('submit', function(e) {
         e.preventDefault();

         $('.add_sale_loading_btn').show();
         var url = $(this).attr('action');
         var request = $(this).serialize();

         $.ajax({
             url: url,
             type: 'post',
             data: request,
             success: function(data) {

                 $('.add_sale_loading_btn').hide();

                 if(!$.isEmptyObject(data.errorMsg)) {

                     toastr.error(data.errorMsg);
                     return;
                 }

                 toastr.success(data);
             },
             error: function(err) {

                 $('.add_sale_loading_btn').hide();
                 $('.error').html('');

                 if (err.status == 0) {

                     toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                     return;
                 } else if (err.status == 500) {

                     toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                     return;
                 } else if (err.status == 403) {

                     toastr.error("{{ __('Access Denied') }}");
                     return;
                 }
             }
         });
     });


    $(document).on('change keypress', 'input', function(e) {

        var nextId = $(this).data('next');

        if (e.which == 13) {

            e.preventDefault();

            $('#' + nextId).focus().select();
        }
    });

     $(document).on('change keypress click', 'select', function(e) {

         var nextId = $(this).data('next');

         if (e.which == 0) {

             $('#' + nextId).focus().select();
         }
     });
 </script>
@endpush
