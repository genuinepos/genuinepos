@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'Purchase Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-sliders-h"></span>
                    <h5>{{ __("Purchase Settings") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>
        <div class="p-1">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="purchase_settings_form" class="setting_form p-3" action="{{ route('purchase.settings.update') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label><b>{{ __('Enable Editing Product Price From Purchase Screen') }}</b></label>
                                    <select name="is_edit_pro_price" class="form-control" id="is_edit_pro_price" autofocus data-next="is_enable_lot_no">
                                        <option value="1">{{ __("Yes") }}</option>
                                        <option {{ $generalSettings['purchase__is_edit_pro_price'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label><b>{{ __('Enable Lot Number') }}</b></label>
                                    <select name="is_enable_lot_no" class="form-control" id="is_enable_lot_no" data-next="save_changes_btn">
                                        <option value="1">{{ __("Yes") }}</option>
                                        <option {{ $generalSettings['purchase__is_enable_lot_no'] == '0' ? 'SELECTED' : '' }} value="0">{{ __("No") }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __("Loading") }}...</span></button>
                                        <button type="submit" id="save_changes_btn" class="btn btn-sm btn-success submit_button float-end">{{ __("Save Changes") }}</button>
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
         $('#purchase_settings_form').on('submit', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('.loading_button').hide();
                    $('#is_edit_pro_price').focus();
                }
            });
        });

        $(document).on('change keypress click', 'select', function(e) {

            var nextId = $(this).data('next');

            if (e.which == 0) {

                $('#' + nextId).focus().select();
            }
        });
    </script>
@endpush
