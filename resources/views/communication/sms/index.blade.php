@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'Sms Server Setup - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Sms Server Setup')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
            </a>
        </div>
    </div>
    <div class="p-3">
       <div class="row">

        <div class="col-md-3 card mb-3">
          <form id="add_data" class="">
                <input type="hidden" name="id" id="edit_input" value="">
                <div class="form-group">
                    <label>Provider</label>
                    <input type="text" name="server_name" class="form-control" autocomplete="off" placeholder="Enter Proider">
                </div>

                <div class="form-group">
                    <label>URL</label>
                    <input type="text" name="host" class="form-control" autocomplete="off" placeholder="Enter Url">
                </div>

                <div class="form-group">
                    <label>Api Key</label>
                    <input type="text" name="api_key"  class="form-control" autocomplete="off" placeholder="Enter Api Key">
                </div>

                <div class="form-group">
                    <label>Sender Id</label>
                    <input type="text" name="sender_id"  class="form-control" autocomplete="off" placeholder="Enter Sender Id">
                </div>

    
                <!-- <div class="form-group">
                    <label>Access Shop</label>
                    <select class="form-control select2"  multiple>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <button type="reset" style="margin:-1px 10px"  class="btn btn-sm btn-danger float-end mr-2">Reset</button>
                    <button style="margin:-1px 10px" type="submit" id="add" class="btn btn-sm btn-success float-end">Save</button>
                </div>
                <br>
          </form>
      </div>



      <div class="panel-body card">
            <div class="row">
              <div class="table-responsive" id="data_list">
                 <table class="display table-hover data_tbl data__table">
                    <thead>
                        <tr class="bg-navey-blue">
                            <th>{{ __('Provider') }}</th>
                            <th>{{ __('URL') }}</th>
                            <th>{{ __('Api Key') }}</th>
                            <th>{{ __('Sender Id') }}</th>
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
   @include('communication.sms.ajax_view.list_js')
   @include('communication.sms.ajax_view.create_js')
   @include('communication.sms.ajax_view.edit_js')
   @include('communication.sms.ajax_view.update_js')
   @include('communication.sms.ajax_view.delete_js')
@endpush
