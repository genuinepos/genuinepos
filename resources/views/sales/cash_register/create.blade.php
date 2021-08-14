@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <form action="{{ route('sales.cash.register.store') }}" method="POST">
                @csrf
                <section class="mt-5">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form_element">
                                    <div class="section-header">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="text-primary">Open Cash Register</h5>
                                                </div>
    
                                                <div class="col-md-6">
                                                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><span class="text-danger">*</span> <b>Cash In Hand :</b> </label>
                                                    <div class="col-8">
                                                        <input required type="number" step="any" name="cash_in_hand" class="form-control" placeholder="Enter Amount" value="0.00">
                                                        <span class="error">{{ $errors->first('cash_in_hand') }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>Cash Counter :</b> </label>
                                                    <div class="col-8">
                                                        <select name="counter_id" class="form-control">
                                                            <option value="">Select Cash Counter</option>
                                                            @foreach ($cashCounters as $cc)
                                                                <option value="{{ $cc->id }}">{{ $cc->counter_name.' ('.$cc->short_name.')' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>B.Location :</b></label>
                                                    <div class="col-8">
                                                        <input readonly type="text" class="form-control" value="{{ auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code : json_decode($generalSettings->business, true)['shop_name'].'(HO)' }}">
                                                    </div>
                                                </div>
                                            </div>

                                            @if (auth()->user()->role_type == 1 || auth()->user()->role_type == 2)
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label class="col-4"><b>Default Account :</b> </label>
                                                        <div class="col-8">
                                                        <select name="account_id" class="form-control">
                                                            <option value="">None</option>
                                                            @foreach ($accounts as $account)
                                                                <option value="{{ $account->id }}">
                                                                    {{ $account->name }} 
                                                                    (A/C: {{ $account->account_number }}) 
                                                                    (Balance: {{ $account->balance }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="submitBtn">
                                            <div class="row justify-content-center">
                                                <div class="col-12 text-end">
                                                    <button type="submit" class="btn btn-sm btn-primary ">
                                                        <b>Submit</b>
                                                    </button>
                                                </div>
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
    
@endpush