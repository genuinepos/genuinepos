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
                    <span class="fas fa-file-upload"></span>
                    <h5>Import Customers </h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
            </div>
        </div>
        <div class="p-3">
            <form id="add_user_form" action="{{ route('contacts.customers.import.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form_element rounded mt-0 mb-3">

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
                                                <button class="btn btn-sm btn-primary float-start m-0">Upload</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>Download Sample :</b> </label>
                                            <div class="col-8">
                                                <a href="{{ asset('import_template/customer_import_template.xlsx') }}" class="btn btn-sm btn-success" download>Download Template File, Click Here</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form_element rounded mt-0 mb-3">
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
                                                <td class="text-start"> @lang('menu.customer') ID </td>
                                                <td class="text-start"> Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">2</td>
                                                <td class="text-start"> @lang('menu.business_name') </td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">3</td>
                                                <td class="text-start"> Name</td>
                                                <td class="text-start text-danger"> Required</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">4</td>
                                                <td class="text-start"> Phone </td>
                                                <td class="text-start text-danger"> <b>Required</b> <br>
                                                    (<small>Must be unique.</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">5</td>
                                                <td class="text-start"> @lang('menu.alternative_number')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">6</td>
                                                <td class="text-start">@lang('menu.landline')</td>
                                                <td class="text-start"> Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">7</td>
                                                <td class="text-start">@lang('menu.email')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">8</td>
                                                <td class="text-start">@lang('menu.date_of_birth')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">9</td>
                                                <td class="text-start">@lang('menu.tax_number')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">10</td>
                                                <td class="text-start">@lang('menu.opening_balance') </td>
                                                <td class="text-start">Optional <br>
                                                    (<small>Opening Balance will be added in customer balance due.</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">12</td>
                                                <td class="text-start">@lang('menu.address')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">@lang('menu.city')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">@lang('menu.state')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">14</td>
                                                <td class="text-start">@lang('menu.country')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">15</td>
                                                <td class="text-start">@lang('menu.zip_code')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">16</td>
                                                <td class="text-start">@lang('menu.shipping_address')</td>
                                                <td class="text-start">Optional</td>
                                            </tr>


                                            <tr>
                                                <td class="text-start">17</td>
                                                <td class="text-start">Pay term Number</td>
                                                <td class="text-start">Optional</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">17</td>
                                                <td class="text-start">@lang('menu.pay_term')</td>
                                                <td class="text-start">Optional (If exists 1=Day,2=Month)</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

