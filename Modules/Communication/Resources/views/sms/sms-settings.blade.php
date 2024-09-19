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
@section('title', 'SMS Settings - ')
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>@lang('menu.sms_settings')</h6>
            <a href="http://erp.test/communication/email/settings" class="btn text-white btn-sm float-end d-lg-block d-none">
                <i class="fa-thin fa-left-to-line fa-2x"></i>
                <br> {{ __('Back') }}
            </a>
        </div>
        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="sms_settings_form" class="setting_form p-3" action="{{ route('communication.sms.settings.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <div class="setting_form_heading">
                                        <h6 class="text-primary"><code>{{ strtoupper($smsSetting['type']) }}</code> REQUEST TO :</h6>
                                        <p class="py-2">
                                        <p class="mb-3"><code style="font-size: 12px;" id="url">{{ $smsSetting['final_url'] }}</code></p>
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group row g-0">
                                    <div class="col-12">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-10">
                                                <input type="text" name="url" class="form-control" placeholder="URL" autocomplete="off" value="{{ $smsSetting['url'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1">
                                                <select name="type" class="form-control" placeholder="">
                                                    <option value="get" {{ $smsSetting['type'] == 'get' ? 'selected' : '' }}>GET</option>
                                                    <option value="post" {{ $smsSetting['type'] == 'post' ? 'selected' : '' }}>POST</option>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary btn-sm  mb-2" id="addMoreButton"><i class="fas fa-plus px-1"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="row g-2" id="keyValueContainer">
                                            <div class="col-12">
                                                @foreach ($smsSetting['config'] as $key => $value)
                                                    <div class="row g-2 mb-3">
                                                        <div class="col-md-11">
                                                            <div class="row g-2">
                                                                <div class="col-md-6 mt-1">
                                                                    <input type="text" name="key[]" class="form-control" placeholder="KEY" autocomplete="off" value="{{ $key ?? '' }}">
                                                                </div>
                                                                <div class="col-md-6 mt-1">
                                                                    <input type="text" name="value[]" class="form-control" placeholder="REPLACING VALUE" autocomplete="off" value="{{ $value ?? '' }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 mt-0">
                                                            <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.parentElement.remove()">x</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-1">
                                        <div class="row mt-4">
                                            <p class="checkbox_input_wrap d-flex align-items-center">
                                                <input type="checkbox" name="status" {{ $smsSetting['status'] === 1 ? 'checked' : '' }}> &nbsp; <b>@lang('menu.is_active')</b>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="btn-box">
                                            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-white"></i></button>
                                            <button class="btn w-auto btn-success submit_button float-end">@lang('menu.save_change')</button>
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

@endsection
@push('scripts')
    <script>
        const urlCode = document.getElementById('url');
        urlCode.addEventListener('click', function() {
            const text = urlCode.textContent;
            // console.log(text);
            // navigator.clipboard.writeText(text);
        });

        $('#sms_settings_form').on('submit', function(e) {
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

        const addButton = document.getElementById('addMoreButton');
        addButton.addEventListener('click', function(e) {
            e.preventDefault();
            const container = document.getElementById('keyValueContainer');
            const child = `<div class="col-12">
                            <div class="row g-2 mb-2">
                                <div class="col-md-11">
                                    <div class="row g-2">
                                        <div class="col-md-6 mt-1">
                                            <input type="text" name="key[]" class="form-control" placeholder="KEY" autocomplete="off">
                                        </div>
                                        <div class="col-md-6 mt-1">
                                            <input type="text" name="value[]" class="form-control" placeholder="REPLACING VALUE" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 mt-0">
                                    <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.parentElement.remove()">x</button>
                                </div>
                            </div>
                        </div>`;
            container.insertAdjacentHTML('beforeend', child);
        });
    </script>
@endpush
