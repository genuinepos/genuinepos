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
@section('title', 'Manufacturing Settings - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <h6>{{ __('Manufacturing Settings') }}</h6>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                                <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <div class="p-1">
                            <div class="card">
                                <form id="update_settings_form" action="{{ route('manufacturing.settings.store.or.update') }}" method="post" class="p-3">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>{{ __('Product Voucher Prefix') }}</strong></label>
                                            <input type="text" name="production_voucher_prefix" class="form-control" id="production_voucher_prefix" placeholder="{{ __('Product Voucher Prefix') }}" value="{{ $manufacturingSetting?->production_voucher_prefix }}" autocomplete="off" data-next="is_edit_ingredients_qty_in_production">
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Enable Editing Ingredients Quantity In Production') }}</strong></label>
                                            <select name="is_edit_ingredients_qty_in_production" class="form-control" id="is_edit_ingredients_qty_in_production" data-next="is_update_product_cost_and_price_in_production">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option @if ($manufacturingSetting) {{ $manufacturingSetting?->is_edit_ingredients_qty_in_production == 0 ? 'SELECTED' : '' }} @endif value="0">
                                                    {{ __('No') }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label><strong>{{ __('Update Product Cost And Selling Price Based On Net Cost') }}</strong> <i data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Update Product Cost And Selling Price Based On Total Production Cost, On Finalizing Production') }}" class="fas fa-info-circle tp"></i></label>
                                            <select name="is_update_product_cost_and_price_in_production" class="form-control" id="is_update_product_cost_and_price_in_production" data-next="save_changes">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option @if ($manufacturingSetting) {{ $manufacturingSetting?->is_update_product_cost_and_price_in_production == 0 ? 'SELECTED' : '' }} @endif value="0">
                                                    {{ __('No') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 d-flex justify-content-end">
                                            <div class="btn-loading">
                                                <button type="button" class="btn loading_button d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                                                <button type="button" id="save_changes" class="btn btn-sm btn-success submit_button float-end">{{ __('Save Changes') }}</button>
                                            </div>
                                        </div>
                                    </div>
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click keypress focus blur change', '.form-control', function(event) {

            $('.brand_submit_button').prop('type', 'button');
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

        var isAllowSubmit = true;
        $(document).on('click', '.submit_button', function() {

            if (isAllowSubmit) {

                $(this).prop('type', 'submit');
            } else {

                $(this).prop('type', 'button');
            }
        });

        $(document).ready(function() {

            $('#update_settings_form').on('submit', function(e) {
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
                        $('#production_voucher_prefix').focus().select();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        if (err.status == 0) {

                            toastr.error("{{ __('Net Connetion Error.') }}");
                            return;
                        } else if (err.status == 500) {

                            toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                            return;
                        } else if (err.status == 403) {

                            toastr.error('Access Denied');
                            return;
                        }
                    }
                });
            });
        });

        $('#production_voucher_prefix').focus().select();
    </script>
@endpush
