@extends('layout.master')
@push('stylesheets')

    <style>
        .list-styled{
            list-style: inside!important;
        }
    </style>
@endpush
@section('title', 'All Sale - ')
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
                                <h5>Version Release Notes</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="row mt-1">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10"><h6>All Release Note.</h6></div>
                            </div>

                            <div class="all-release-note-area p-2">
                                <div class="version-release-note mt-1">
                                    <div class="release-version">
                                        <h5 class="text-blue">Release: 1.5.1</h5>
                                    </div>
                                    <div class="release-list p-1">
                                        <ul class="list-styled">
                                            <li>Stylized links created for Ashlar are now available in the Lite template. A demo is available at</li>
                                            <li>Stylized links created for Ashlar are now available in the Lite template. A demo is available at</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="changes-log-area p-2 mt-5">
                                <div class="release-version">
                                    <h5 class="text-blue">Change Log</h5>
                                </div>
                                <div class="changes-log mt-1">
                                    <ul class="list-styled">
                                        <li>Date-26/08/2021 (Refactor SaleController, PosController code. Modify util class.)</li>
                                        <li>Date-26/08/2021 (Add yajra table in customer table, Fixed customer view page design.)</li>
                                        <li>Date-26/08/2021 (Fixed quick add product form design.)</li>
                                        <li>Date-28/08/2021 (Manufacturing settings is complated.)</li>
                                        <li>Date-28/08/2021 (Manufacturing process(recipe) section is going...)</li>
                                        <li>Date-31/08/2021 (Manufacturing product section- properly calculate ingredients input qty by output qty...)</li>
                                        <li>Date-31/08/2021 (changes add product form desing...)</li>
                                        <li>Date-31/08/2021 (Fixed some major problems...)</li>
                                        <li>Date-31/08/2021 (select table row...)</li>
                                        <li>Date-31/08/2021 (focus first field when user click edit button...)</li>
                                        <li>Date-31/08/2021 (added a colums in addons table --column=e_commerce...)</li>
                                    </ul>
                                </div>
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