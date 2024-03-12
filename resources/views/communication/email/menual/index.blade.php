@extends('layout.master')
@push('stylesheets')
    <style>
        .top-menu-area ul li {display: inline-block; margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray; padding: 1px 5px; border-radius: 3px; font-size: 11px;}
    </style>
@endpush
@section('title', 'Manual Mail - ')
@section('content')
<div class="body-woaper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('Manual Mail')</h6>
            </div>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
            </a>
        </div>
    </div>
    <div class="p-3">
       <div class="row">

       <div class="col-md-5 card mb-3">
       
         <div class="row mt-3">

            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="active" value="1" onchange="handleRadioChange(this)">
                    <label class="form-check-label" for="active"> <span class="ml-3 mt-3"> All </span></label>
                </div>
            </div>

            <div class="col-md-2">
                 <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="inactive" value="2" onchange="handleRadioChange(this)">
                    <label class="form-check-label" for="inactive"> <span class="ml-3 mt-3"> Customer </span></label>
                </div>
            </div>

            <div class="col-md-2">
                 <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="other1" value="3" onchange="handleRadioChange(this)">
                    <label class="form-check-label" for="other1"> <span class="ml-3 mt-3"> Supplier </span></label>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="other2" value="4" onchange="handleRadioChange(this)">
                    <label class="form-check-label" for="other2"> <span class="ml-3 mt-3"> User </span></label>
                </div>
            </div>
              <div class="table-responsive" id="data_list">
                 <table class="display table-hover data_tbl data__table">
                    <thead>
                        <tr class="bg-navey-blue">
                            <th>{{ __('Check') }}</th>
                            <th>{{ __('Email') }}</th>
                         </tr>
                       </thead>
                       <tbody></tbody>
                    </table>
                </div>
         </div>
      </div>

       <div class="col-md-7 card mb-3">
       <strong> Manual Mail </strong>
         <form id="add_data" class="">
                <input type="hidden" name="id" id="edit_input" value="">
                <div class="col-md-12 row">
                    <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Sender</label>
                        <select class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label>Select Body</label>
                        <select class="form-control">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>To</label>
                            <input type="email" name="port"  class="form-control" autocomplete="off" placeholder="Ex. demo@email.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                       <button style="margin-top:3px;" type="button" class="btn btn-info brn-sm mt-3">+</button>
                   </div>
                </div>
                <br><br> <br>
                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" name="port"  class="form-control" autocomplete="off" placeholder="CC: E.x. abs@example.com, xyz@wxample.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <input type="email" name="port"  class="form-control" autocomplete="off" placeholder="BCC: E.x. abs@example.com, xyz@wxample.com">
                   </div>
                </div>
                <br><br>
                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="email" name="port"  class="form-control" autocomplete="off" placeholder="Enter Subject">
                        </div>
                    </div>
                </div>

                  <div class="row">
                        <div class="col-md-12">
                            <label><strong>Body </strong><span class="text-danger">*</span></label>
                            <textarea name="message" class="ckEditor form-control" cols="50" rows="5" tabindex="4" style="display: none; width: 653px; height: 160px;" data-next="save_and_new"></textarea>
                        </div>
                    </div>


                <div class="form-group mt-3 mb-5">
                     <button style="margin:-1px 22px" type="submit" id="add" class="btn btn-success float-end">Send Email</button>
                </div>
                <br>
          </form>
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
                   writer.setStyle('min-height', height + 'px', newEditor.editing.view.document
                    .getRoot());
             });
                window.editors[index] = newEditor
            });
        });
   </script>
   @include('communication.email.menual.ajax_view.list_js')
@endpush
