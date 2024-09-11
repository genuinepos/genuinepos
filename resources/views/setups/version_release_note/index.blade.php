@extends('layout.master')
@push('stylesheets')
    <style>
        .list-styled {
            list-style: inside !important;
        }
    </style>
@endpush
@section('title', 'Version Release Notes - ')
@section('content')
    <div class="body-woaper" style="font-family:Arial, Helvetica, sans-serif!important;">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h5>{{ __('Version Release Notes') }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="card">
                <div class="all-release-note-area p-2">
                    <div class="version-release-note mt-1">
                        <div class="release-version">
                            <h5 class="text-blue">{{ __('Last Release') }}: 2.0.6</h5>
                        </div>
                        <div class="release-list p-1">
                            <ul class="list-styled">
                                <ol class="fw-bold">{{ __('About This Update') }}</ol>
                                <li>{{ __('Upgrade to the new version of Gposs System preformance is optimized, fixed some bugs, added and stability is enhenced, bring your butter experience.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="changes-log-area p-2 mt-3">
                    <div class="release-version">
                        <h5 class="text-blue">{{ __('Update Log') }}</h5>
                    </div>
                    <div class="changes-log mt-1">
                        <ul class="list-styled">
                            <ol class="fw-bold">{{ __('Fixed Some Bugs') }}</ol>
                            <li> - {{ __('Role permission mismatch issue is fixed') }}</li>
                            <li> - {{ __('Service Invoice/Sale delete issue is fixed') }}</li>
                            <ol class="fw-bold">{{ __('Feature Update') }}</ol>
                            <li> - {{ __('Add Sales , POS Sales and Service sales in one list (Manage Sales)') }}</li>
                            <li> - {{ __('Device, Device Model, and Serial Number Label name change option is added to service settings') }}</li>
                            <li> - {{ __('Added new role permission called [View Only Own Created Transactions/Data]') }}</li>
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="all-release-note-area p-2">
                    <div class="version-release-note mt-1">
                        <div class="release-version">
                            <h5 class="text-blue">{{ __('Release') }}: 2.0.5</h5>
                        </div>
                        <div class="release-list p-1">
                            <ul class="list-styled">
                                <ol class="fw-bold">{{ __('About This Update') }}</ol>
                                <li>{{ __('Upgrade to the new version of Gposs System preformance is optimized, fixed some bugs, added new settings to Company/Store settings and stability is enhenced, bring your butter experience.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="changes-log-area p-2 mt-3">
                    <div class="release-version">
                        <h5 class="text-blue">{{ __('Update Log') }}</h5>
                    </div>
                    <div class="changes-log mt-1">
                        <ul class="list-styled">
                            <ol class="fw-bold">{{ __('Fixed Some Bugs') }}</ol>
                            <li> - {{ __('Supplier Opening Balance Update issue is fixed') }}</li>
                            <li> - {{ __('Create/Update Bank Account error is fixed') }}</li>
                            <li> - {{ __('POS Screen selling price group bug is fixed') }}</li>
                            <li> - {{ __('Purchase order to invoice supplier current balance issue is fixed') }}</li>
                            <ol class="fw-bold">{{ __('Feature Update') }}</ol>
                            <li> - {{ __('Add New settings to Company/Store setting [1.Auto Repay: Due Sales/P.Returns (Receipt), 2. Auto Repay: Purchases/S.Returns (Payment)]') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
