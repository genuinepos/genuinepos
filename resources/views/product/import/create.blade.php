@extends('layout.master')
@section('content')
    <style>
        .card-custom {background: white;border-radius: 6px;border-top: 2px solid #3699ff;}
        .instruction_table table tbody td {font-size: 12px;}
        .instruction_table table th,td {color: #495677;}
    </style><br><br><br>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->

            
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 style="color: #32325d">Import Products</h3> 
                    </div>
                    <div class="col-md-6">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-info float-end"><i class="fas fa-long-arrow-alt-left"></i> Back</a>
                    </div>
                </div>
                <!--begin::Form-->
                <form action="{{ route('product.import.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="card-body card-custom mt-1">    
                        <div class="form-group row">
                            <div class="col-md-4">
                                <strong>File To Import:</strong>
                                <input type="file" name="import_file" class="form-control-file form-control-sm">
                                <span class="error" style="color: red;">{{ $errors->first('import_file') }}</span><br>
                                <a href="{{ asset('public/import_template/product_import_template.csv') }}" class="mt-2" download>Download Template File, Click Here</a>
                            </div>
                            <div class="col-md-4 ">
                                <button type="submit" value="save" class="btn btn-success submit_button btn-sm mt-5">Upload File</button>
                            </div>
                        </div>
                        
                       
                    </div>     
                </form>
                <!--end::Form-->
                <div class="card-body card-custom mt-5">    
                    <div class="heading"><h4>Instructions</h4></div>
                    <div class="top_note">
                        <p class="p-0 m-0"><b>Follow the instructions carefully before importing the file.</b></p>
                        <p>The columns of the file should be in the following order.</p>
                    </div>
                    <div class="instruction_table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Column Number</th>
                                    <th>Column Name</th>
                                    <th>Instruction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td> Product Name (Required)</td>
                                    <td> Name of the product</td>
                                </tr>

                                <tr>
                                    <td>2</td>
                                    <td> Product code(SKU) (Optional)</td>
                                    <td>Product code(SKU). If blank an SKU will be automatically generated</td>
                                </tr>

                                <tr>
                                    <td>3</td>
                                    <td> Unit (Required)</td>
                                    <td> Name of the unit</td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td> Category (Required)</td>
                                    <td> <b>Name of the Category</b> <br>
                                        (<small>If not found new category with the given name will be created</small>)</td>
                                </tr>

                                <tr>
                                    <td>5</td>
                                    <td> Child category (Optional)</td>
                                    <td> <b>Name of the Sub-Category</b> <br>
                                        (<small>If not found new sub-category with the given name under the
                                            parent Category will be created</small>)</td>
                                </tr>

                                <tr>
                                    <td>6</td>
                                    <td>Brand (Optional)</td>
                                    <td> <b>Name of the brand</b> <br>
                                        (<small>If not found new brand with the given name will be created</small>)</td>
                                </tr>

                                <tr>
                                    <td>7</td>
                                    <td>Barcode Type (Optional, Default: C128)</td>
                                    <td> Barcode Type for the product. <br>
                                        (<span><b>Currently supported: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</b> </span>)</td>
                                </tr>

                                <tr>
                                    <td>8</td>
                                    <td>Alert quantity (Optional)</td>
                                    <td> Alert quantity</td>
                                </tr>

                                <tr>
                                    <td>9</td>
                                    <td>Expiry Date (Optional)</td>
                                    <td>Stock Expiry Date <br>
                                        (<span><b>Format: mm-dd-yyyy; Ex: 11-25-2018</b> </span>)
                                    </td>
                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td>Warranty</td>
                                    <td>Name of the Warranty </td>
                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td>Description (Optional)</td>
                                    <td>Description of product </td>
                                </tr>

                                <tr>
                                    <td>12</td>
                                    <td>Tax (Optional)</td>
                                    <td>Only in numbers</td>
                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>UNIT COST Excluding Tax (Required)</td>
                                    <td>Only in numbers</td>
                                </tr>

                                <tr>
                                    <td>13</td>
                                    <td>UNIT COST Including Tax (Optional)</td>
                                    <td>Only in numbers</td>
                                </tr>

                                <tr>
                                    <td>14</td>
                                    <td>Profit Margin % (Optional)</td>
                                    <td>
                                        Profit Margin (Only in numbers) 
                                    </td>
                                </tr>

                                <tr>
                                    <td>15</td>
                                    <td>Opening Stock (Only in numbers)</td>
                                    <td>
                                        Selling Price (Only in numbers) 
                                    </td>
                                </tr>

                                <tr>
                                    <td>16</td>
                                    <td>Opening stock Branch (Optional) <br>
                                        (<small>If blank first Branch will be used</small>)  </td>
                                    <td>
                                        Only Branch Code
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
<script>
    @if (Session::has('errorMsg'))
        toastr.error('{{ session('errorMsg') }}');
    @endif
</script>