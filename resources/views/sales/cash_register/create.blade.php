@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
            padding: 0px;
            margin: 0px;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endpush
@section('title', 'Cash Register - ')
@section('content')

    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Cash Register') }}</h6>
                </div>

                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}</a>
            </div>
        </div>

        <div class="p-1">
            <form action="{{ route('cash.register.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $saleId }}">
                <input type="hidden" name="job_card_id" value="{{ $jobCardId }}">
                <input type="hidden" name="sale_screen_type" value="{{ $saleScreenType }}">
                <section>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="text-primary">{{ __('Open Cash Register') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-2"> <b>{{ __('Cash A/c') }} </b> <span class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <select name="cash_account_id" id="cash_account_id" class="form-control">
                                                        <option value="">{{ __('Select Cash A/c') }}</option>
                                                        @foreach ($cashAccounts as $cashAccount)
                                                            <option {{ old('cash_account_id') == $cashAccount->id ? 'SELECTED' : '' }} value="{{ $cashAccount->id }}">{{ $cashAccount->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error">{{ $errors->first('cash_account_id') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-2"><b>{{ __('Cash Counter') }}</b> <span class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <select required name="cash_counter_id" class="form-control">
                                                        <option value="">{{ __('Select Cash Counter') }}</option>
                                                        @foreach ($cashCounters as $cc)
                                                            <option {{ old('cash_counter_id') == $cc->id ? 'SELECTED' : '' }} value="{{ $cc->id }}">{{ $cc->counter_name . ' (' . $cc->short_name . ')' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error">{{ $errors->first('cash_counter_id') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-2"> <b>{{ __('Opening Cash Amount') }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="number" step="any" name="opening_cash" class="form-control fw-bold" id="opening_cash" placeholder="{{ __('Opening Cash Amount') }}" value="0.00">
                                                    <span class="error">{{ $errors->first('opening_cash') }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-2"><b>{{ __('Sales Ledger A/c') }}</b> </label>
                                                <div class="col-8">
                                                    <select name="sale_account_id" class="form-control" id="sale_account_id" data-next="price_group_id">
                                                        @foreach ($saleAccounts as $saleAccount)
                                                            <option value="{{ $saleAccount->id }}">
                                                                {{ $saleAccount->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error">{{ $errors->first('sale_account_id') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4 text-end pe-2"><b>{{ location_label() }}</b></label>
                                                <div class="col-8">
                                                    <input readonly type="text" class="form-control fw-bold" value="{{ $branchName }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="submitBtn">
                                        <div class="row justify-content-center">
                                            <div class="col-12 text-end">
                                                <button type="submit" class="btn btn-sm btn-success ">
                                                    <b>{{ __('Submit') }}</b>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    @include('sales.cash_register.js_partial.create_js')
@endpush
