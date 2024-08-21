@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'Email Body Format - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Email Body Format')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
            </a>
        </div>
    </div>
    <div class="p-3">
       <div class="row">

        <div class="col-md-6 card mb-3">
          <form id="add_data" class="">

                <input type="hidden" name="id" id="edit_input" value="">

                <div class="form-group">
                    <label>{{ __('Format Name') }}</label>
                    <input type="text" name="format" class="form-control" autocomplete="off" placeholder="Format Name">
                </div>

                <div class="form-group">
                    <label>{{ __('Subject') }}</label>
                    <input type="text" name="subject" class="form-control" autocomplete="off" placeholder="Email Subject">
                </div>

                <div class="form-group">
                    <label>{{ __('Body') }}</label>
                    <textarea name="body" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="width: 653px; height: 160px;" data-next="save_and_new"></textarea>
                </div>

                <div class="form-group">
                    <label>{{ __('Important') }}</label>
                    <select class="form-control" name="is_important">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
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
                            <th>{{ __('Important') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Subject') }}</th>
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
 <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
   <script>
    window.editors = {};
    document.querySelectorAll('.ckEditor').forEach((node, index) => {
        ClassicEditor
            .create(node, {})
            .then(newEditor => {
                newEditor.editing.view.change(writer => {
                    var height = node.getAttribute('data-height');
                    writer.setStyle('min-height', height + 'px', newEditor.editing.view.document.getRoot());
                });
                window.editors[index] = newEditor
            });
    });
   </script>
   @include('communication.email.body.ajax_view.list_js')
   @include('communication.email.body.ajax_view.create_js')
   @include('communication.email.body.ajax_view.edit_js')
   @include('communication.email.body.ajax_view.update_js')
   @include('communication.email.body.ajax_view.delete_js')
@endpush
