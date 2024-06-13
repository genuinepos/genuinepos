@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Bank List - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">

            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __("Banks") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="section-header">
                    <div class="col-6">
                        <h6>{{ __("List of Banks") }}</h6>
                    </div>

                    <div class="col-6 d-flex justify-content-end">
                        <a href="{{ route('banks.create') }}" class="btn btn-sm btn-primary" id="addBankBtn"><i class="fas fa-plus-square"></i> {{ __("Add") }}</a>
                    </div>
                </div>

                <div class="widget_content">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table bank_table">
                            <thead>
                                <tr>
                                    <th class="text-start">{{ __("S/L") }}</th>
                                    <th class="text-start">{{ __("Name") }}</th>
                                    <th class="text-start">{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

    <!-- Add Modal -->
    <div class="modal fade" id="bankAddOrEditModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    </div>
@endsection
@push('scripts')
    @include('accounting.banks.js_partial.index_js')
@endpush
