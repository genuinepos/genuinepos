@extends('layout.master')
@push('stylesheets')
   
@endpush
@section('title', 'Selling Price Groups - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-shopping-cart"></span>
                                <h5>Selling Price Group</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>

                        <div class="sec-name">
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <p class="mb-3"><b>Import/Export Selling Price Group Prices</b> </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"><b>Import File :</b> </label>
                                            <div class="col-8">
                                                <input type="file" name="import_file" class="form-control">
                                                <span class="error" style="color: red;">
                                                    {{ $errors->first('import_file') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <label for="inputEmail3" class="col-4"></label>
                                            <div class="col-8">
                                                <button class="btn btn-sm btn-primary float-end mt-1">Upload Import File</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <div class="col-12">
                                                <a href="{{ asset('public/import_template/product_import_template.csv') }}" class="btn btn-sm btn-success" download>Export Selling Price Group Prices</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 d-none d-md-block">
                                <div class="col-md-12">
                                    <div class="heading"><h4>Instructions</h4></div>
                                    <div class="top_note">
                                        <p class="p-0 m-0">
                                            <b>•</b> Export Selling price group prices.
                                        </p>
                                        <p class="p-0 m-0">
                                            <b>•</b> Update the exported file and import the same file.
                                        </p>
                                        <p class="p-0 m-0">
                                            <b>•</b> Only selling price group prices of the product will be updated. Any blank price will be skipped.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>All Selling Price Group</h6>
                                    </div>
                                  
                                    <div class="col-md-2">
                                        <div class="btn_30_blue float-end">
                                            <a href=""><i class="fas fa-plus-square"></i> Add</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>S/L</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <form id="deleted_form" action="" method="post">
                                    @method('DELETE')
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush