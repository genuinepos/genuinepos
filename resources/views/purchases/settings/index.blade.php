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
                    <h5>Purchase Settings</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i
                        class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <div class="p-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="purchase_settings_form" class="setting_form p-3" action="{{ route('purchase.settings.store') }}" method="post">
                            @csrf

                            <div class="form-group row">
                                <div class="col-md-7">
                                    <div class="row mt-2">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                {{ json_decode($generalSettings->purchase, true)['is_edit_pro_price'] == '1' ? 'CHECKED' : '' }}
                                                name="is_edit_pro_price"> &nbsp; <b>Enable editing  product price from purchase screen</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="row mt-2">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                {{ json_decode($generalSettings->purchase, true)['is_enable_status'] == '1' ? 'CHECKED' : '' }}
                                                name="is_enable_status"> &nbsp; <b>Enable Purchase Status</b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-4">
                                    <div class="row mt-2">
                                        <p class="checkbox_input_wrap">
                                            <input type="checkbox"
                                                {{ json_decode($generalSettings->purchase, true)['is_enable_lot_no'] == '1' ? 'CHECKED' : '' }}
                                                name="is_enable_lot_no"> &nbsp; <b>Enable Lot number</b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="btn-loading">
                                        <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner"></i><span> Loading...</span></button>
                                        <button class="btn btn-sm btn-success submit_button float-end">Save Change</button>
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
                }
            });
        });
    </script>
@endpush
