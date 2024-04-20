<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Uploaded Documents') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="document-list-modal">
            <div class="row">
                <div class="col-md-12">
                    <div class="header">
                        <p><b>{{ __('Docs') }}</b></p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table modal-table table-sm">
                            <thead class="bg-secondary">
                                <tr>
                                    <th class="text-start text-white">#</th>
                                    <th class="text-start text-white">{{ __('File') }}</th>
                                    <th class="text-start text-white">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($attachments) > 0)
                                    @foreach ($attachments as $attachment)
                                        <tr>
                                            <td class="text-start">{{ $loop->index + 1 }}</td>
                                            <td class="text-start">
                                                @if ($attachment->extension == 'png' || $attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'gif' || $attachment->extension == 'svg' || $attachment->extension == 'webp')
                                                    <a data-magnify="gallery" data-caption="ddd" data-group="" href="{{ asset('uploads/workspace_attachments/' . $attachment->attachment) }}">
                                                        <img style="height: 35px;width:40px;" src="{{ asset('uploads/' . tenant('id') . '/' . 'workspace_attachments/' . $attachment->attachment) }}">
                                                    </a>
                                                @else
                                                    <i class="far fa-file"></i> <span class="text-muted">{{ $attachment->attachment }}</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                <a data-magnify="gallery" data-caption="ddd" data-group="" href="{{ asset('uploads/' . tenant('id') . '/' . 'workspace_attachments/' . $attachment->attachment) }}" class="btn btn-sm btn-info text-white">{{ __('View') }}</a>
                                                <a href="{{ asset('uploads/' . tenant('id') . '/' . 'workspace_attachments/' . $attachment->attachment) }}" class="btn btn-sm btn-secondary" download>{{ __('Download') }}</a>
                                                <a href="{{ route('workspaces.attachments.delete', $attachment->id) }}" id="attachmentDelete" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th class="text-center" colspan="3">{{ __('No Data Found') }}</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <form id="deleted_attachment_form" action="" method="post">
                            @method('DELETE')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('[data-magnify=gallery]').magnify();
</script>
