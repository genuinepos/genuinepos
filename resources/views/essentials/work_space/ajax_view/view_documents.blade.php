<div class="row">
    <div class="col-md-12">
        <div class="header">
            <p><b>Docs</b></p>
        </div>
    </div>

    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table modal-table table-sm">
                <thead class="bg-secondary">
                    <tr>
                        <th class="text-start text-white">#</th>
                        <th class="text-start text-white">File</th>
                        <th class="text-start text-white">@lang('menu.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($docs) > 0)
                        @foreach ($docs as $doc)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    @if ($doc->extension == 'png' || $doc->extension == 'jpg' || $doc->extension == 'jpeg' || $doc->extension == 'gif' || $doc->extension == 'svg' || $doc->extension == 'webp')
                                    <a data-magnify="gallery" data-caption="ddd" data-group="" href="{{ asset('uploads/workspace_docs/'.$doc->attachment) }}">
                                        <img style="height: 35px;width:40px;" src="{{ asset('uploads/workspace_docs/'.$doc->attachment) }}">
                                    </a>
                                    @else
                                    <i class="far fa-file"></i> <span class="text-muted">{{ $doc->attachment }}</span>
                                    @endif
                                </td>
                                <td class="text-start">
                                    <a data-magnify="gallery" data-caption="ddd" data-group="" href="{{ asset('uploads/workspace_docs/'.$doc->attachment) }}" class="btn btn-sm btn-info text-white">View</a>
                                    <a href="{{ asset('uploads/workspace_docs/'.$doc->attachment) }}" class="btn btn-sm btn-secondary" download>Download</a>
                                    <a href="{{ route('workspace.delete.doc', $doc->id) }}" id="delete_doc" class="btn btn-sm btn-danger">@lang('menu.delete')</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th class="text-center" colspan="3">No Data Found.</th>
                        </tr>
                    @endif
                </tbody>
            </table>
            <form id="deleted_doc_form" action="" method="post">
                @method('DELETE')
                @csrf
            </form>
        </div>
    </div>
</div>
<div class="d-flex justify-content-end">
    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
</div>
<script>
    $('[data-magnify=gallery]').magnify();
</script>
