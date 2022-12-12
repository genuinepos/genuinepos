@extends('layout.master')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('backend/asset/css/richtext.min.css') }}">
    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .table-responsive-y {
            max-height: 350px;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .form-check {
            padding: 0;
            gap: 5px
        }
        .form-check-input {
            margin-left: 0 !important;
            margin-top: -2px !important;
        }
        .barcode-preview {
            width: 100%;
            height: 250px;
            overflow-y: scroll;
        }
        .color-label {
            line-height: 27px;
            width: 100%;
            height: 25px;
            background: #babfc4;
            text-align: center;
        }
        .txt-sm {
            font-size: 80%;
            line-height: 150%;
        }
        .btn.btn-sm {
            margin-top: -3px;
        }
    </style>

@endpush
@section('title', 'Email Setup Design Pages - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-print"></span>
                    <h5>Print Barcode</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="">Settings Name</label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="" id="" class="form-control">
                                        <option value="">My Settings</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                        <option value="">3</option>
                                        <option value="">4</option>
                                        <option value="">5</option>
                                    </select>
                                    <p><small class="text-secondary">Type: CODE128 Product Code: Hide Company: Show Product Name: Show Price: 1</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <span>Manual selection</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label for="">@lang('menu.product_name')</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" placeholder="Search Product">
                                </div>
                            </div>

                            <div class="table-responsive-y">
                                <table class="display table table-striped">
                                    <thead>
                                        <tr class="bg-secondary text-white">
                                            <th>SL</th>
                                            <th>Code</th>
                                            <th>@lang('menu.product_name')</th>
                                            <th>@lang('menu.selling_price')</th>
                                            <th>Print Qty</th>
                                            <th><i class="fas fa-times"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td><i class="fas fa-times"></i></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td><i class="fas fa-times"></i></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td><i class="fas fa-times"></i></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td><i class="fas fa-times"></i></td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td><i class="fas fa-times"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-between g-2 pt-3">
                                <div class="col-md-3 col-sm-4">
                                    <div class="row g-2">
                                        <label for="" class="col-sm-6 col-4 text-sm-end">Column</label>
                                        <div class="col-sm-6 col-8">
                                            <input class="form-control" type="number" name="" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="row g-2">
                                        <label for="" class="col-sm-6 col-4 text-sm-end">Row</label>
                                        <div class="col-sm-6 col-8">
                                            <input class="form-control" type="number" name="" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row g-2">
                                        <label for="" class="col-sm-6 col-4">Total Print Qty</label>
                                        <div class="col-sm-6 col-8">
                                            <input class="form-control" type="number" name="" id="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-sm-flex gap-1 justify-content-between pt-3">
                                <button class="btn btn-sm btn-primary m-0">Complete Print & Clear Data</button>
                                <div>
                                    <button class="btn btn-sm btn-success m-0">Print Preview</button>
                                    <button class="btn btn-sm btn-success m-0">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-9">
                                    <div class="btn-box">
                                        <button class="btn btn-sm btn-danger">Reset Form</button>
                                        <button class="btn btn-sm btn-success">Generate Settings</button>
                                    </div>
                                </div>
                                <div class="col-sm-3 d-flex justify-content-end">
                                    <div class="barcode-preview overflow-hidden h-auto">
                                        <img src="{{ asset ('assets/images/barcode.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <span>Purchased Product List</span>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive-y">
                                <table class="table display table-striped">
                                    <thead>
                                        <tr>
                                            <th>Check</th>
                                            <th> SL</th>
                                            <th>@lang('menu.product_name')</th>
                                            <th>Code</th>
                                            <th>@lang('menu.price')</th>
                                            <th>@lang('menu.supplier_name')</th>
                                            <th>@lang('menu.qty')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>4</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>2</td>
                                            <td>3</td>
                                            <td>4</td>
                                            <td>5</td>
                                            <td>6</td>
                                            <td>7</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5">
        <div class="p-3">
            <div class="card mb-3">
                <div class="card-header border-0">
                    <strong></strong>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <span>Preview</span>
                        </div>
                        <div class="card-body">
                            <form action="">
                                <div class="row g-2 mb-3">
                                    <div class="col-sm-7">
                                        <div class="row g-2">
                                            <label for="" class="col-4">Sttings Name</label>
                                            <div class="col-8">
                                                <input type="text" class="form-control" placeholder="E.x. Barcode CODE128">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 d-flex justify-content-end">
                                        <a role="button" class="btn btn-sm btn-primary">Settings List</a>
                                    </div>
                                </div>
                            </form>
                            <div class="card barcode-preview mb-3">
                                <div class="row g-1">
                                    <div class="col-sm-3">
                                        <img src="{{ asset ('assets/images/barcode.png') }}" alt="">
                                    </div>
                                    <div class="col-sm-3">
                                        <img src="{{ asset ('assets/images/barcode.png') }}" alt="">
                                    </div>
                                    <div class="col-sm-3">
                                        <img src="{{ asset ('assets/images/barcode.png') }}" alt="">
                                    </div>
                                    <div class="col-sm-3">
                                        <img src="{{ asset ('assets/images/barcode.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between">
                                <button class="btn btn-sm btn-success">Save Settings</button>
                                <div>
                                    <button class="btn btn-sm btn-secondary">Print Preview</button>
                                    <button class="btn btn-sm btn-success">Print</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <span>Page & Print Setup</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <span>Page Margin (Pixel)</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label for="">Top</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Right</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Left</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Bottom</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card mb-2">
                                        <div class="card-header">
                                            <span>Barcode Row & Column Qty</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label for="">Column</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Row</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Max Barcode Qty</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <span>Column & Row Space (Pixel)</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label for="">Column</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                                <div class="col-6">
                                                    <label for="">Row</label>
                                                    <input type="number" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form action="" class="card">
                        <div class="card-body">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <span>Barcode Properties</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-8">
                                            <label for="">Barcode Value</label>
                                            <input type="number" class="form-control mb-1" value="123456789">
                                            <label for="">@lang('menu.barcode_type')</label>
                                            <select name="" class="form-control mb-2" id="">
                                                <option value="">CODE128</option>
                                                <option value="">CODE128</option>
                                                <option value="">CODE128</option>
                                                <option value="">CODE128</option>
                                            </select>
                                            <div class="row g-2 mb-2">
                                                <div class="col-10">
                                                    <label for="barcodeBgColor" class="color-label">Background Color</label>
                                                </div>
                                                <div class="col-2">
                                                    <input type="color" name="" id="barcodeBgColor" value="#ffffff" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-10">
                                                    <label for="barcodeForeColor" class="color-label">Foreground Color</label>
                                                </div>
                                                <div class="col-2">
                                                    <input type="color" name="" id="barcodeForeColor" value="#000000" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Barcode Image Size</label>
                                            <div class="row g-0 align-items-end mb-2">
                                                <div class="col-4">
                                                    <label for="">Width: </label>
                                                </div>
                                                <div class="col-8">
                                                    <div class="row g-0">
                                                        <label for="" class="col-6 txt-sm">inches</label>
                                                        <label for="" class="col-6 txt-sm">pixels</label>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control">
                                                        <input type="number" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-0 align-items-end mb-2">
                                                <div class="col-4">
                                                    <label for="">Height: </label>
                                                </div>
                                                <div class="col-8">
                                                    <div class="row g-0">
                                                        <label for="" class="col-6 txt-sm">inches</label>
                                                        <label for="" class="col-6 txt-sm">pixels</label>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control">
                                                        <input type="number" class="form-control" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-0 pt-1">
                                                <label for="" class="col-4">DPI</label>
                                                <div class="col-8">
                                                    <input type="number" name="" id="" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <span>Human Readable Text</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="showProductCodeCheck">
                                                <label class="form-check-label" for="showProductCodeCheck">
                                                    Show Product Code
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="showBorderCheck">
                                                <label class="form-check-label" for="showBorderCheck">
                                                    Show Border
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="CompanyNameCheck">
                                                <label class="form-check-label" for="CompanyNameCheck">
                                                    Company Name
                                                </label>
                                            </div>
                                            <div class="row g-1">
                                                <div class="col-8">
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn btn-sm btn-primary">Select Font</button>
                                                </div>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="productNameCheck">
                                                <label class="form-check-label" for="productNameCheck">
                                                    @lang('menu.product_name')
                                                </label>
                                            </div>
                                            <div class="row g-1">
                                                <div class="col-8">
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-4">
                                                    <button class="btn btn-sm btn-primary">Select Font</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="">Line Height</label>
                                            <input type="number" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="textAlign" id="textAlign1">
                                                <label class="form-check-label" for="textAlign1">
                                                    Text Right
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="textAlign" id="textAlign2">
                                                <label class="form-check-label" for="textAlign2">
                                                    Text Left
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="textAlign" id="textAlign3">
                                                <label class="form-check-label" for="textAlign3">
                                                    Text Center
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="showPriceCheck">
                                                <label class="form-check-label" for="showPriceCheck">
                                                    Show Price
                                                </label>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-sm-4">
                                                    <div class="row g-0">
                                                        <label for="" class="col-6">Currency</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control" value="BDT">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="row g-0">
                                                        <label for="" class="col-6">@lang('menu.amount')</label>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" placeholder="E.x. (inc. vat)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row g-2">
                                                <div class="col-sm-6">
                                                    <div class="row g-0">
                                                        <label for="" class="col-6">Alignment</label>
                                                        <div class="col-6">
                                                            <select name="" id="" class="form-control">
                                                                <option value="">Center</option>
                                                                <option value="">Top</option>
                                                                <option value="">Bottom</option>
                                                                <option value="">Left</option>
                                                                <option value="">Right</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-primary m-0">Select Font</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <button class="btn btn-sm btn-danger">@lang('menu.reset')</button>
                                <button class="btn btn-sm btn-success">Preview</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
