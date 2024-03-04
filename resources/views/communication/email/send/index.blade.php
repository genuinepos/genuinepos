
@extends('layout.master')
@push('stylesheets')
    <style>
        .mail-sidebar .nav-link {
            color: #333;
            border-radius: 0 25px 25px 0;
            color: #000000;
        }

        .mail-icon {
            display: inline-block;
            width: 30px;
            text-align: center;
        }

        .mail-sidebar .nav-link .count {
            float: right;
            font-weight: 600;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #cbe4ee;
            color: #000000;
        }

        .card-body {
            padding: 7px;
        }

        .btn {
            line-height: 1.4;
            --bs-btn-focus-box-shadow: 0 0 0 0.18rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
            font-size: 13px;
            border-radius: 5px;
            /* border-width: 0; */
            /* border-bottom: 3px solid rgba(0, 0, 0, 0.3); */
            transition: color .25s ease-in-out, background-color .25s ease-in-out, border-color .25s ease-in-out, box-shadow .25s ease-in-out;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .g-1,
        .gy-1 {
            --bs-gutter-y: 0.25rem;
        }

        .g-1,
        .gx-1 {
            --bs-gutter-x: 0.25rem;
        }

        .email-content .row {
            height: 100%;
        }

        .email-content {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .email-content {
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .btn.btn-sm {
            height: 32px;
            line-height: 23px;
            padding: 0 5px;
            font-size: 12px;
        }
    </style>
@endpush
@section('title', 'Email Section - ')
@section('content')

 <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('Email Section')</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>
        </div>
        <div class="p-3">
                 <div class="row g-1">


                    <!-- modal for compose email -->
            @include('communication.email.send.modal.add')


                  <div class="col-md-2 card mb-3">
                    <div class="mail-sidebar" style="height:840px;">
                        <div class="card-body">
                            <button class="btn btn-primary rounded-pill mb-2" data-bs-toggle="modal"
                                data-bs-target="#VairantChildModal"><i class="fa-light fa-pen-fancy"></i> Compose</button>
                            <ul class="nav nav-pills flex-column">

                                <li class="nav-item">
                                    <a href="#" id="v-pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-home" class="nav-link active">
                                        <span class="mail-icon"><i class="fa-duotone fa-mailbox"></i></span> Inbox
                                        <span class="count" id="email_count">0</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" id="v-pills-profile-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-profile" class="nav-link">
                                        <span class="mail-icon"><i class="fa-duotone fa-envelope"></i></span> Sent
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" id="v-pills-messages-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-messages" class="nav-link">
                                        <span class="mail-icon"><i class="fa-duotone fa-file-lines"></i></span> Drafts
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" id="v-pills-settings-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-settings" class="nav-link">
                                        <span class="mail-icon"><i class="fa-duotone fa-filters"></i></span> Junk
                                        <span class="count">65</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" id="v-pills-settings-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-settingss" class="nav-link font-weight-bold"
                                        id="trashed_item" showtrash="true">
                                        <span class="mail-icon"><i class="fa-duotone fa-trash-arrow-up"></i></span>
                                        Trash
                                        <span class="count" id="trashed_email_count">2</span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
              <div class="col-md-10 card mb-3">
                                            <div class="mailbox-controls1 mt-2">
                                                <button type="button" class="btn btn-secondary checkbox-toggle"
                                                    id="check_all" title="Check"><i class="far fa-square"></i></button>
                                                <input type="checkbox" id="is_check_all" class="d-none">
                                                <div class="btn-group d-none" id="bulkDeleteButton">
                                                    <button type="button" class="btn btn-secondary all_delete"
                                                        title="Bulk Delete">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                        Delete
                                                    </button>
                                                </div>
                                                <div class="btn-group" id="bulkForceDeleteButton">
                                                    <button type="button" class="btn btn-secondary all_delete"
                                                        title="Bulk Delete">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                        Permanent Delete
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="tab-content" id="v-pills-tabContent">
                                                <div class="tab-pane fade show" id="v-pills-home" role="tabpanel"
                                                    aria-labelledby="v-pills-home-tab">1</div>
                                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                                    aria-labelledby="v-pills-profile-tab">...2</div>
                                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                                    aria-labelledby="v-pills-messages-tab">3...</div>
                                                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                                    aria-labelledby="v-pills-settings-tab">.4..</div>
                                                <div class="tab-pane fade" id="v-pills-settingss" role="tabpanel"
                                                    aria-labelledby="v-pills-settings-tab">.5..</div>
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
                   writer.setStyle('min-height', height + 'px', newEditor.editing.view.document
                    .getRoot());
             });
                window.editors[index] = newEditor
            });
        });
</script>
@include('communication.email.body.ajax_view.create_js')
@endpush
