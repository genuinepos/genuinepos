@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'Email Server Setup - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Email Server Setup')</h6>
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
                    <label>Server</label>
                    <input type="text" name="server_name" class="form-control" autocomplete="off" placeholder="Ex. smtp">
                </div>

                <div class="form-group">
                    <label>Host</label>
                    <input type="text" name="host" class="form-control" autocomplete="off" placeholder="Ex. smtp.gmail.com">
                </div>

                <div class="form-group">
                    <label>Port</label>
                    <input type="text" name="port"  class="form-control" autocomplete="off" placeholder="Ex. 587">
                </div>

                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" name="user_name"  class="form-control" autocomplete="off" placeholder="Ex. @username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password"  class="form-control" autocomplete="off" placeholder="Ex. ******">
                </div>

                <div class="form-group">
                    <label>Encryption</label>
                    <input type="text" name="encryption"  class="form-control" autocomplete="off" placeholder="Ex. Tis/Ssl">
                </div>

                <div class="form-group">
                    <label>Sender Mail</label>
                    <input type="email" name="address"  class="form-control" autocomplete="off" placeholder="Ex. xyz@email.com">
                </div>

                <div class="form-group">
                    <label>Sender Name</label>
                    <input type="text" name="name"  class="form-control" autocomplete="off" placeholder="Ex. Mr.Xyz">
                </div>

                <div class="form-group">
                    <label>Access Shop</label>
                    <select class="form-control select2"  multiple>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

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


        <div class="col-md-9 card mb-3">
                <div class="form-group">
                    <label>Server</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. smtp">
                </div>

                <div class="form-group">
                    <label>Host</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. smtp.gmail.com">
                </div>

                <div class="form-group">
                    <label>Port</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. 587">
                </div>

                <div class="form-group">
                    <label>User Name</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. @username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" autocomplete="off" placeholder="Ex. ******">
                </div>

                <div class="form-group">
                    <label>Encryption</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. Tis/Ssl">
                </div>

                <div class="form-group">
                    <label>Sender Mail</label>
                    <input type="email" class="form-control" autocomplete="off" placeholder="Ex. xyz@email.com">
                </div>

                <div class="form-group">
                    <label>Sender Name</label>
                    <input type="text" class="form-control" autocomplete="off" placeholder="Ex. Mr.Xyz">
                </div>

                <div class="form-group mt-3">
                    <button type="reset" style="margin:-1px 10px"  class="btn btn-sm btn-danger float-end mr-2">Reset</button>
                    <button style="margin:-1px 10px" type="button"  class="btn btn-sm btn-success float-end">Save</button>
                </div>
                <br>
        </div>



      <div class="panel-body card">
            <div class="row">
              <div class="table-responsive" id="data_list">
                 <table class="display table-hover data_tbl data__table">
                    <thead>
                        <tr class="bg-navey-blue">
                            <th>{{ __('Server Name') }}</th>
                            <th>{{ __('Host') }}</th>
                            <th>{{ __('Port') }}</th>
                            <th>{{ __('User Name') }}</th>
                            <th>{{ __('Password') }}</th>
                            <th>{{ __('Encryption') }}</th>
                            <th>{{ __('Address') }}</th>
                            <th>{{ __('Name') }}</th>
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
   @include('communication.email.ajax_view.list_js')
   @include('communication.email.ajax_view.create_js')
   @include('communication.email.ajax_view.edit_js')
   @include('communication.email.ajax_view.update_js')
   @include('communication.email.ajax_view.delete_js')
@endpush
