<div class="row g-1 contact_group_list tab_contant active">
    <div class="col-md-4">
        <div class="card mb-0" id="add_form_number_div">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Add Contacts </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2">
                <form id="add_number_form" action="{{ route('communication.contacts.number.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label><b>Group </b> <span class="text-danger">*</span></label>
                        <select name="group_name" required class="form-control submit_able" id="group_name" autofocus>
                            <option value="">Select Group</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error error_group_name"></span>
                    </div>
                    <div class="form-group">
                        <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="name" class="form-control" id="name" placeholder="@lang('menu.name')" />
                        <span class="error error_name"></span>
                    </div>
                    <div class="form-group">
                        <label><b>@lang('menu.phone_number') </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="phone_number" class="form-control" id="phone_number" placeholder="@lang('menu.phone_number')" />
                        <span class="error error_phone_number"></span>
                    </div>
                    <div class="form-group">
                        <label><b>Whatsapp Number </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="whatsapp_number" class="form-control" id="phone_number" placeholder="Whatsapp Number" />
                        <span class="error error_whatsapp_number"></span>
                    </div>

                    <div class="form-group">
                        <label><b>@lang('menu.email') </b> <span class="text-danger">*</span></label>
                        <input required type="email" name="email" class="form-control" id="email" placeholder="Email" />
                        <span class="error error_email"></span>
                    </div>

                    <div class="form-group">
                        <label><b>Mailing Address </b> <span class="text-danger">*</span></label>
                        <input required type="text" name="mailing_address" class="form-control" id="mailing_address" placeholder="Mailing Address" />
                        <span class="error error_mailing_address"></span>
                    </div>

                    <div class="form-group row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn loading_button d-none"><i
                                    class="fas fa-spinner text-primary"></i><b> @lang('menu.loading')</b></button>
                            <button type="submit"
                                class="c-btn button-success me-0 float-end submit_button">@lang('menu.save')</button>
                            <button type="reset" class="c-btn btn_orange float-end">@lang('menu.reset')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mb-0 d-none" id="edit_number_form">
            <div class="section-header">
                <div class="col-md-12">
                    <h6>Edit Contacts </h6>
                </div>
            </div>

            <div class="form-area px-3 pb-2" id="edit_number_form_body"></div>
        </div>
    </div>


    <div class="col-md-8">
        <div class="card mb-0">
            <div class="section-header">
                <div class="col-md-6">
                    <h6>Contact List</h6>
                </div>
            </div>

            <div class="widget_content">
                <div class="data_preloader">
                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                </div>
                <div class="table-responsive" id="data-list">
                    <table class="display data_tbl data__table groupNumberTable">
                        <thead>
                            <tr class="bg-navey-blue">
                                <th class="text-black">@lang('menu.sl')</th>
                                <th class="text-black">Group</th>
                                <th class="text-black">@lang('menu.name')</th>
                                <th class="text-black">@lang('menu.phone_number')</th>
                                <th class="text-black">Whatsapp Number</th>
                                <th class="text-black">@lang('menu.email')</th>
                                <th class="text-black">Mailing Address</th>
                                <th class="text-black">@lang('menu.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="deleted_number_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>
