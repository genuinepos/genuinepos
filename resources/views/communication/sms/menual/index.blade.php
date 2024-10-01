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

        .bg-navy-blue {
            background-color: black;
            color: white;
        }
    </style>
@endpush
@section('title', 'Manual Sms - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('Manual Sms')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_data" class="" method="post" action="{{ route('menual-sms.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-5 card mb-3">
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="active" value="1" onchange="handleRadioChange(this)">
                                    <label class="form-check-label" for="active"> <span class="ml-3 mt-3"> All </span></label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="inactive" value="2" onchange="handleRadioChange(this)">
                                    <label class="form-check-label" for="inactive"> <span class="ml-3 mt-3"> Customer </span></label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="other1" value="3" onchange="handleRadioChange(this)">
                                    <label class="form-check-label" for="other1"> <span class="ml-3 mt-3"> Supplier </span></label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="other2" value="4" onchange="handleRadioChange(this)">
                                    <label class="form-check-label" for="other2"> <span class="ml-3 mt-3"> User </span></label>
                                </div>
                            </div>
                            <div class="table-responsive" id="data_list">
                                <input class="btn btn-info btn-sm" type="button" onclick='selects()' value="Check All" />
                                <input class="btn btn-default btn-sm" type="button" onclick='deSelect()' value="Uncheck All" />
                                <table class="display table-hover data_tbl data__table">
                                    <thead>
                                        <tr style="background:black;color:white;">
                                            <th>{{ __('Check') }}</th>
                                            <th>{{ __('Mobile') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 card mb-3">
                        <strong> Manual Sms</strong>
                        <input type="hidden" name="id" id="edit_input" value="">
                        <div class="col-md-12 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Sender</label>
                                    <select class="form-control" name="sender_id">
                                        <option value="">Select Sender</option>
                                        @foreach ($sender as $senders)
                                            <option value="{{ $senders->id }}">{{ $senders->host }} - {{ $senders->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Body</label>
                                    <select id="bodyId" class="form-control" onchange="handlebody(this)">
                                        <option value="">Select Body</option>
                                        @foreach ($body as $bodys)
                                            <option value="{{ $bodys->id }}">{{ $bodys->format }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To</label>
                                        <div id="smsContainer">
                                            <input type="text" name="mobile[]" class="form-control add_input" placeholder="Ex. 018....">
                                            <span class="error error_to"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button id="addMoreButton" style="margin-top:3px;margin-left:12px;" type="button" class="btn btn-info brn-sm mt-3">+</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label><strong>Body </strong><span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" cols="50" rows="5"></textarea>
                                </div>
                            </div>

                            <div class="form-group mt-3 mb-5">
                                <button style="margin:-1px 22px" type="submit" id="add" class="btn btn-success float-end">Send Sms</button>
                            </div>
                            <br>
                        </div>
                    </div>
            </form>
        </div>

    @endsection

    @push('scripts')
        @include('communication.sms.menual.ajax_view.list_js')
    @endpush
