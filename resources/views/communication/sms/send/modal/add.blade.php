   <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog select_variant_modal_dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{ __("New Message") }}</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <form id="mail_send" enctype="multipart/form-data">

                    <div class="row" data-select2-id="select2-data-5-qlgx">
                        <div class="col-5">
                            <button type="button" class="btn btn-success btn-sm mb-2 p-1" id="addMoreButton"><i
                                    class="fas fa-plus"></i> {{ __("Add More Phone") }}</button>
                        </div>

                        <div class="col-7" data-select2-id="select2-data-4-t4zu">

                     <select name="group_id" class="form-control">
                        <option value="" selected>Select Group</option>
                        <option value="all">All</option>
                        <option value="customer">Customer</option>
                        <option value="supplier">Supplier</option>
                        <option value="user">User</option>
                    </select>

                        </div>
                    </div>

                    <div class="row" id="to_area">
                       <div class="col-md-12">
                            <label><strong>To</strong> <span class="text-danger">*</span></label>
                            <div id="emailContainer">
                                <input required="" type="number" name="phone[]" class="form-control add_input" placeholder="Phone">
                                <span class="error error_to"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Body </strong></label>
                             <textarea name="message" class="form-control" cols="50" rows="5"></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="loading-btn-box">
                                <button type="submit"
                                    class="btn btn-sm btn-success float-end submit_button">Save</button>
                                <button type="reset" data-bs-dismiss="modal"
                                    class="btn btn-sm btn-danger float-end me-2">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
