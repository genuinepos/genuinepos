<form id="edit_card_type_form" action="{{ route('settings.payment.card.types.update', $cardType->id) }}" method="POST">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>Type Name :</b> <span class="text-danger">*</span></label>
            <input type="text" name="card_type_name" class="form-control" id="e_card_type_name"
                placeholder="Card Type name" value="{{ $cardType->card_type_name }}"/>
            <span class="error error_e_card_type_name"></span>
        </div>
    </div>

    <div class="form-group row mt-1">
        <div class="col-md-12">
            <label><b>Default Payment Account :</b> </label>
            <select name="account_id" id="account_id" class="form-control">
                <option value="">Select Default Payment Account</option>
                @foreach ($accounts as $account)
                    <option {{ $cardType->account_id == $account->id ? 'SELECTED' : '' }} value="{{ $account->id }}">
                        {{ $account->name.' (A/C:'.$account->account_number.')' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12">
            <button type="button" class="btn loading_button d-none"><i
                    class="fas fa-spinner text-primary"></i><b> Loading...</b></button>
            <button type="submit" class="c-btn btn_blue me-0 float-end submit_button">Save</button>
            <button type="reset" data-bs-dismiss="modal"
                class="c-btn btn_orange float-end">Close</button>
        </div>
    </div>
</form>