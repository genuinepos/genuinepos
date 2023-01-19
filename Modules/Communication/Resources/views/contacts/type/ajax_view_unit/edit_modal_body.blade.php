<form id="update_group_form" action="{{ route('communication.contacts.group.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $groups->id }}">
    <div class="form-group">
        <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
        <input required type="text" name="name" class="form-control " id="e_name" placeholder="units name"
            value="{{ $groups->name }}" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b>
                    @lang('menu.loading')</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end" id="update_group_btn">@lang('menu.save_change')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_group_form">@lang('menu.close')</button>
        </div>
    </div>
</form>
