   <div class="modal fade" id="VairantChildModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog select_variant_modal_dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">{{ __("New Message") }}</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <form id="mail_send" action="" method="POST" enctype="multipart/form-data">

                    <div class="row" data-select2-id="select2-data-5-qlgx">
                        <div class="col-5">
                            <button type="button" class="btn btn-primary btn-sm mb-2 p-1" id="addMoreButton"><i
                                    class="fas fa-plus"></i> Add More Emails</button>
                        </div>

                        <div class="col-7" data-select2-id="select2-data-4-t4zu">
                            
                        <select name="group_id[]" class="form-control select2 form-select select2-hidden-accessible" multiple="" data-select2-id="select2-data-1-ojy1" tabindex="-1" aria-hidden="true" placeholder="Select group(s)">
                            <option value="">All</option>
                            <option value="">Customer</option>
                            <option value="">Supplier</option>
                            <option value="">User</option>
                        </select>

                        </div>
                    </div>

                    <div class="row" id="to_area">
                       <div class="col-md-12">
                            <label><strong>To</strong> <span class="text-danger">*</span></label>
                            <div id="emailContainer">
                                <input required="" type="email" name="to[]" class="form-control add_input" data-name="To" placeholder="Email">
                                <span class="error error_to"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Subject</strong> <span class="text-danger">*</span></label>
                            <input required="" type="text" name="subject" class="form-control add_input"
                                data-name="Subject" id="subject" placeholder="Subject">
                            <span class="error error_subject"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Attachment </strong></label>
                            <input type="file" name="file" class="form-control add_input" data-name="file"
                                id="file" placeholder="Attachments" multiple="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Body </strong></label>
                            <textarea class="ckEditor" name="description"></textarea>
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
