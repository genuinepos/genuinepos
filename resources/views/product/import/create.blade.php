@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {border: 1px solid #7e0d3d;}
        b{font-weight: 500;font-family: Arial, Helvetica, sans-serif;}
    </style>
@endpush
@section('title', __('Import Products - '))
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Import Products") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>
        <form id="add_user_form" action="{{ route('product.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="p-lg-1 p-1">
                <div class="row g-lg-3 g-1">
                    <div class="col-12">
                        <div class="form_element rounded m-0">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __("File To Import") }}</b> </label>
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
                                                <button class="btn btn-sm btn-primary">{{ __("Upload") }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('Download Simple') }} </b> </label>
                                            <div class="col-8">
                                                <a href="{{ asset('import_template/product_import_template.xlsx') }}" class="btn btn-sm btn-success" download>{{ __("Download Template File, Click Here") }}</a>
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
                                <div class="heading"><h4>{{ __("Instructions") }}</h4></div>
                                <div class="top_note">
                                    <p class="p-0 m-0"><b>{{ __("Follow the instructions carefully before importing the file.") }}</b></p>
                                    <p>{{ __("The columns of the file should be in the following order.") }}</p>
                                </div>

                                <div class="instruction_table">
                                    <table class="display table table-sm modal-table">
                                        <thead>
                                            <tr >
                                                <th class="text-start">{{ __("Column Number") }}</th>
                                                <th class="text-start">{{ __("Column Name") }}</th>
                                                <th class="text-start">{{ __("Instruction") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-start">{{ __("1") }}</td>
                                                <td class="text-start">{{ __("Product Name") }}</td>
                                                <td class="text-start">
                                                    {{ __("Required") }}
                                                    <br>
                                                    <small class="text-danger">{{ __("If empty row will be skipped.") }}</small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("2") }}</td>
                                                <td class="text-start">{{ __("Product Code") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">{{ __("Product Code(SKU). If blank a product code, It will be generated automatically.") }}</small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("3") }}</td>
                                                <td class="text-start">{{ __("Unit ID") }}</td>
                                                <td class="text-start">
                                                    {{ __("Required") }}
                                                    <br>
                                                    <small class="text-danger">{{ __("If column is empty, Then row will be skipped.") }}</small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("4") }}</td>
                                                <td class="text-start">{{ __("Category ID") }}</td>
                                                <td class="text-start">{{ __("Optional") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("5") }}</td>
                                                <td class="text-start">{{ __("Subcategory ID") }}</td>
                                                <td class="text-start">{{ __("Optional") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("6") }}</td>
                                                <td class="text-start">{{ __("Brand ID") }}</td>
                                                <td class="text-start">{{ __("Optional") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("7") }}</td>
                                                <td class="text-start">{{ __("Warranty ID") }}</td>
                                                <td class="text-start">{{ __("Optional") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("8") }}</td>
                                                <td class="text-start">{{ __("Stock Type") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    {{ __("1=Physical Product/Manageable Stock, 2=Service/Digital Product") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("If column is empty, Automatically set Physical Product") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("9") }}</td>
                                                <td class="text-start">{{ __("Alert Quantity") }}</td>
                                                <td class="text-start">{{ __("Optional") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("10") }}</td>
                                                <td class="text-start">{{ __("Tax Percent") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("Keep only the amount like: 5.00, Don't use (%)") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("11") }}</td>
                                                <td class="text-start">{{ __("Tax Type") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("(Example: 1=Exclusive Tax, 2=Inclusive Tax), If this column is empty, then it will be set 1 = Exclusive type automatically.") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("12") }}</td>
                                                <td class="text-start">{{ __("Unit Cost (Excluded Tax)") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("13") }}</td>
                                                <td class="text-start">{{ __("Unit Cost (Included Tax)") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("14") }}</td>
                                                <td class="text-start">{{ __("Selling Price (Excluded Tax)") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("15") }}</td>
                                                <td class="text-start">{{ __("Enable IMEI/SL No.") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("If the product IMEI/Serial No, so type YES, If this column is empty, then it will be set NO automatically.") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("16") }}</td>
                                                <td class="text-start">{{ __("Is For Sale") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("If the product is not for sale, so type NO, If this column is empty, then it will be set YES automatically.") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("17") }}</td>
                                                <td class="text-start">{{ __("Enable Batch No/Expire Date") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("If the has Batch No/Expire Date, so type Yes, If this column is empty, then it will be set NO automatically.") }}
                                                    </small>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __("18") }}</td>
                                                <td class="text-start">{{ __("Opening Stock") }}</td>
                                                <td class="text-start">
                                                    {{ __("Optional") }}
                                                    <br>
                                                    <small class="text-danger">
                                                        {{ __("Opening stock will be added to the loggedin user current shop.") }}
                                                    </small>
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

@push('scripts')
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif
    </script>
@endpush

