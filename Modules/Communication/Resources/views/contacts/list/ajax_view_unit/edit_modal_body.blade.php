<form id="update_number_form" action="{{ route('communication.contacts.number.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" id="id" value="{{ $number->id }}">

    <div class="form-group">
        <label><b>Group </b> <span class="text-danger">*</span></label>
        <select name="group_name" required class="form-control submit_able" id="e_group_name" autofocus>
            <option value="">Select Group</option>
            @foreach ($groups as $group)
                <option value="{{ $group->id }}" {{$group->id == $number->group_id ? 'SELECTED' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
        <span class="error error_e_group_name"></span>
    </div>

    <div class="form-group">
        <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
        <input required type="text" name="name" class="form-control " id="name" placeholder="@lang('menu.name')"
            value="{{ $number->name }}" />
        <span class="error error_e_name"></span>
    </div>

    <div class="form-group">
        <label><b>@lang('menu.phone_number')</b> <span class="text-danger">*</span></label>
        <input required type="text" name="phone_number" class="form-control " id="phone_number" placeholder="@lang('menu.phone_number')"
            value="{{ $number->phone_number }}" />
        <span class="error error_e_phone_number"></span>
    </div>

    <div class="form-group">
        <label><b>Whatsapp Number </b> <span class="text-danger">*</span></label>
        <input required type="text" name="whatsapp_number" class="form-control " id="phone_number" placeholder="Whatsapp Number"
            value="{{ $number->whatsapp_number }}" />
        <span class="error error_e_whatsapp_number"></span>
    </div>

    <div class="form-group">
        <label><b>@lang('menu.email') </b> <span class="text-danger">*</span></label>
        <input required type="email" name="email" class="form-control " id="email" placeholder="Email"
            value="{{ $number->email }}" />
        <span class="error error_e_email"></span>
    </div>

    <div class="form-group">
        <label><b>Mailing Address </b> <span class="text-danger">*</span></label>
        <input required type="text" name="mailing_address" class="form-control " id="mailing_address" placeholder="Mailing Address"
            value="{{ $number->mailing_address }}" />
        <span class="error error_e_mailing_address"></span>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i class="fas fa-spinner text-primary"></i><b>
                    @lang('menu.loading')</b></button>
            <button type="submit" class="c-btn button-success me-0 float-end" id="update_number_btn">@lang('menu.save_change')</button>
            <button type="button" class="c-btn btn_orange float-end" id="close_number_form">@lang('menu.close')</button>
        </div>
    </div>
</form>
