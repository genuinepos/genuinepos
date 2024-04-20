@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', ' - Advertisement List ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Advertisement List')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}
            </a>
        </div>
    </div>
    <div class="p-3">
       <div class="row">
     <div class="col-md-12 card mb-3">
         <div class="panel-body card">
              <div class="table-responsive" id="data_list">
                 <table class="display table-hover data_tbl data__table">
                    <thead>
                        <tr class="bg-navey-blue">
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Attachment') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                         </tr>
                       </thead>
                       <tbody></tbody>
                    </table>
                </div>
           </div>
       </div>
    </div>
</div>

@endsection

@push('scripts')
   @include('advertisement.ajax_view.list_js')
@endpush
