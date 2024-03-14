@extends('layout.master')
@push('stylesheets')
    <style>
        .form_element {
            border: 1px solid #7e0d3d;
        }

        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
@endpush
@section('title', __('Import Suppliers'))
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Import Suppliers') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>
        <form action="{{ route('contacts.suppliers.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="container-fluid p-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label class="col-4"><b>{{ __('File To Import') }}</b></label>
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
                                                <button class="btn btn-sm btn-primary float-start m-0">{{ __('Upload') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>{{ __('Download Sample') }}</b> </label>
                                            <div class="col-8">
                                                <a href="{{ asset('import_template/supplier_import_template.xlsx') }}" class="btn btn-sm btn-success" download>{{ __('Download Template File, Click Here') }}</a>
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
                                <div class="heading">
                                    <h4>{{ __('Instructions') }}</h4>
                                </div>
                                <div class="top_note">
                                    <p class="p-0 m-0"><b>{{ __('Follow the instructions carefully before importing the file.') }}</b></p>
                                    <p>{{ __('The columns of the file should be in the following order.') }}</p>
                                </div>

                                <div class="instruction_table">
                                    <table class="display table table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-start">{{ __('Column Number') }}</th>
                                                <th class="text-start">{{ __('Column Name') }}</th>
                                                <th class="text-start">{{ __('Instruction') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-start">{{ __('1') }}</td>
                                                <td class="text-start">{{ __('Supplier ID') }}</td>
                                                <td class="text-start text-danger">{{ __("Supplier ID will be generated automatically.") }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('2') }}</td>
                                                <td class="text-start">{{ __('Name') }}</td>
                                                <td class="text-start text-danger">
                                                    {{ __('Required') }}
                                                    <br>
                                                    (<small>{{ __('If empty row will be skipped.') }}</small>)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('3') }}</td>
                                                <td class="text-start">{{ __('Phone') }}</td>
                                                <td class="text-start text-danger">
                                                    <b>{{ __('Required') }}</b> <br>
                                                    (<small>{{ __('Must Be Unique.') }}</small>)
                                                    <br>
                                                    (<small>{{ __('If empty row will be skipped.') }}</small>)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('4') }}</td>
                                                <td class="text-start">{{ __('Business') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('5') }}</td>
                                                <td class="text-start">{{ __('Alternative Phone Number') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('6') }}</td>
                                                <td class="text-start">{{ __('Landline') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('7') }}</td>
                                                <td class="text-start">{{ __('Email') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('8') }}</td>
                                                <td class="text-start">{{ __('Tax Number') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('9') }}</td>
                                                <td class="text-start">{{ __('Address') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('10') }}</td>
                                                <td class="text-start">{{ __('City') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('11') }}</td>
                                                <td class="text-start">{{ __('State') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('12') }}</td>
                                                <td class="text-start">{{ __('Zip-code') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('13') }}</td>
                                                <td class="text-start">{{ __('Country') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('14') }}</td>
                                                <td class="text-start">{{ __('Pay-Term Number') }}</td>
                                                <td class="text-start">{{ __('Optional') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">{{ __('15') }}</td>
                                                <td class="text-start">{{ __('Pay-Term') }}</td>
                                                <td class="text-start">
                                                    {{ __('Optional') }} ({{ __('If exists 1=Days,2=Months') }})
                                                    <br>
                                                    (<small>{{ __('Example: Pay-Term Number : 2, Pay-Term : 2') }} = {{ __('2 Months') }}</small>)
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
