@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-file-import"></span>
                    <h5>Import Products </h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
            </div>
        </div>
        <form id="add_user_form" action="{{ route('product.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="p-3">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="form_element rounded m-0">

                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>File To Import :</b> </label>
                                            <div class="col-8">
                                                <input type="file" name="import_file" class="form-control">
                                                <span class="error" style="color: red;">
                                                    {{ $errors->first('import_file') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="col-8">
                                                <button class="btn btn-sm btn-primary float-start">Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>Download Simple :</b> </label>
                                            <div class="col-8">
                                                <a href="{{ asset('import_template/product_import_template.csv') }}" class="btn btn-sm btn-success" download>Download Template File, Click Here</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <div class="heading"><h4>Instructions</h4></div>
                                <div class="top_note">
                                    <p class="p-0 m-0"><b>Follow the instructions carefully before importing the file.</b></p>
                                    <p>The columns of the file should be in the following order.</p>
                                </div>

                                <div class="instruction_table">
                                    <table class="table table-sm modal-table table-striped">
                                        <thead>
                                            <tr >
                                                <th class="text-start">Column Number</th>
                                                <th class="text-start">Column Name</th>
                                                <th class="text-start">Instruction</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-start">1</td>
                                                <td class="text-start"> Product Name (Required)</td>
                                                <td class="text-start"> Name of the product</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">2</td>
                                                <td class="text-start"> Product code(SKU) (Optional)</td>
                                                <td class="text-start">Product code(SKU). If blank an SKU will be automatically generated</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">3</td>
                                                <td class="text-start"> Unit (Required)</td>
                                                <td class="text-start"> Name of the unit</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">4</td>
                                                <td class="text-start"> Category (Required)</td>
                                                <td class="text-start"> <b>Name of the Category</b> <br>
                                                    (<small>If not found new category with the given name will be created</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">5</td>
                                                <td class="text-start"> Child category (Optional)</td>
                                                <td class="text-start"> <b>Name of the Sub-Category</b> <br>
                                                    (<small>If not found new sub-category with the given name under the
                                                        parent Category will be created</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">6</td>
                                                <td class="text-start">Brand (Optional)</td>
                                                <td class="text-start"> <b>Name of the brand</b> <br>
                                                    (<small>If not found new brand with the given name will be created</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">7</td>
                                                <td class="text-start">Barcode Type (Optional, Default: C128)</td>
                                                <td class="text-start"> Barcode Type for the product. <br>
                                                    (<span><b>Currently supported: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</b> </span>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">8</td>
                                                <td class="text-start">Alert quantity (Optional)</td>
                                                <td class="text-start"> Alert quantity</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">9</td>
                                                <td class="text-start">Expiry Date (Optional)</td>
                                                <td class="text-start">Stock Expiry Date <br>
                                                    (<span><b>Format: mm-dd-yyyy; Ex: 11-25-2018</b> </span>)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">10</td>
                                                <td class="text-start">Warranty</td>
                                                <td class="text-start">Name of the Warranty </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">11</td>
                                                <td class="text-start">@lang('menu.description') (Optional)</td>
                                                <td class="text-start">Description of product </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">12</td>
                                                <td class="text-start">Tax (Optional)</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">UNIT COST Excluding Tax (Required)</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">UNIT COST Including Tax (Optional)</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">14</td>
                                                <td class="text-start">Profit Margin % (Optional)</td>
                                                <td class="text-start">
                                                    Profit Margin (Only in numbers)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">15</td>
                                                <td class="text-start">Opening Stock (Only in numbers)</td>
                                                <td class="text-start">
                                                    Selling Price (Only in numbers)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">16</td>
                                                <td class="text-start">Opening stock Branch (Optional) <br>
                                                    (<small>If blank first Branch will be used</small>)  </td>
                                                <td class="text-start">
                                                    Only Branch Code
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
@endsection

