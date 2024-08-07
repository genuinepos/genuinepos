<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Edit Store') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="edit_branch_form" action="{{ route('branches.update', $branch->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-9" style="border-right: 1px solid #000;">
                        <input type="hidden" name="branch_type" id="branch_type" value="{{ $branch->branch_type }}">
                        <input type="hidden" name="parent_branch_id" id="parent_branch_id" value="{{ $branch->parent_branch_id }}">
                        @if ($branch->branch_type == \App\Enums\BranchType::ChainShop->value)
                            <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <label><b>{{ __('Parent Store') }}</b> </label>
                                    <input readonly type="text" name="name" class="form-control fw-bold" value="{{ $branch?->parentBranch?->name . '/' . $branch?->parentBranch?->branch_code }}" />
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-3 col-md-6">
                                    <label> <b>{{ __('Store Type') }}</b></label>
                                    <select name="branch_type" class="form-control" id="branch_type" data-next="branch_name">
                                        @foreach (\App\Enums\BranchType::cases() as $branchType)
                                            <option {{ $branchType->value == $branch->branch_type ? 'SELECTED' : '' }} value="{{ $branchType->value }}">{{ preg_replace('/[A-Z]/', ' ' . "$0", $branchType->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-6 parent_branches_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'd-hide' }}">
                                    <label> <b>{{ __('Parent Store') }}</b> <span class="text-danger">*</span></label>
                                    <select name="parent_branch_id" class="form-control" id="branch_parent_branch_id" data-next="branch_code">
                                        <option value="">{{ __('Select Parent Store') }}</option>
                                        @foreach ($branches as $br)
                                            <option {{ $br->id == $branch->parent_branch_id ? 'SELECTED' : '' }} value="{{ $br->id }}">{{ $br->name . ' / ' . $br->branch_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                        @endif

                        <div class="form-group row mt-1">

                            @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                                <div class="col-lg-3 col-md-6 branch_name_field">
                                    <label><b>{{ __('Store Name') }}</b> <span class="text-danger">*</span></label>
                                    <input required type="text" name="name" class="form-control" id="branch_name" data-next="area_name" value="{{ $branch->name }}" placeholder="{{ __('Store Name') }}" />
                                    <span class="error error_branch_name"></span>
                                </div>

                                {{-- <div class="col-lg-3 col-md-6 branch_name_field {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}">
                                    <label><b> {{ __('Store Name') }}</b> <span class="text-danger">*</span></label>
                                    <input {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? '' : 'required' }} type="text" name="name" class="form-control" id="branch_name" data-next="area_name" value="{{ $branch->name }}" placeholder="{{ __('Store Name') }}" />
                                    <span class="error error_branch_name"></span>
                                </div> --}}
                            @endif

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Area Name') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="area_name" class="form-control" id="area_name" data-next="branch_code" value="{{ $branch->area_name }}" placeholder="{{ __('Area Name') }}" />
                                <span class="error error_branch_code"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Phone') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="phone" class="form-control" data-name="Phone number" id="branch_phone" data-next="branch_alternate_phone_number" value="{{ $branch->phone }}" placeholder="{{ __('Phone No') }}" />
                                <span class="error error_branch_phone"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Store ID') }}</b></label>
                                <input required readonly type="text" name="branch_code" class="form-control fw-bold" id="branch_code" data-next="branch_phone" value="{{ $branch->branch_code }}" placeholder="{{ __('Store ID') }}" />
                                <span class="error error_branch_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Alternative Phone') }}</b> </label>
                                <input type="text" name="alternate_phone_number" class="form-control" id="branch_alternate_phone_number" data-next="branch_bin" value="{{ $branch->alternate_phone_number }}" placeholder="{{ __('Alternative Phone') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Business Indentification No.') }} </label>
                                <input type="text" name="bin" class="form-control" id="branch_bin" data-next="branch_tin" value="{{ $branch->bin }}" placeholder="{{ __('Business Indentification Number') }}" autocomplete="off" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label class="fw-bold">{{ __('Vat/Tax No.') }} </label>
                                <input type="text" name="tin" class="form-control" id="branch_tin" data-next="branch_country" value="{{ $branch->tin }}" placeholder="{{ __('Vat/Tax Number.') }}" autocomplete="off" />
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Country') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="country" class="form-control" id="branch_country" data-next="branch_state" value="{{ $branch->country }}" placeholder="{{ __('Country') }}" />
                                <span class="error error_branch_country"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('State') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="state" class="form-control" id="branch_state" data-next="branch_city" value="{{ $branch->state }}" placeholder="{{ __('State') }}" />
                                <span class="error error_branch_state"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label> <b>{{ __('City') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="city" class="form-control" id="branch_city" data-next="branch_zip_code" value="{{ $branch->city }}" placeholder="{{ __('City') }}" />
                                <span class="error error_branch_city"></span>
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Zip-Code') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="zip_code" class="form-control" id="branch_zip_code" data-next="branch_address" value="{{ $branch->zip_code }}" placeholder="Zip code" />
                                <span class="error error_branch_zip_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-6 col-md-6">
                                <label><b>{{ __('Address') }}</b> <span class="text-danger">*</span></label>
                                <input required type="text" name="address" class="form-control" id="branch_address" data-next="branch_email" value="{{ $branch->address }}" placeholder="{{ __('Address') }}" />
                                <span class="error error_branch_zip_code"></span>
                            </div>
                        </div>

                        <div class="form-group row mt-1">
                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Email') }}</b></label>
                                <input type="text" name="email" class="form-control" id="branch_email" data-next="branch_website" value="{{ $branch->email }}" placeholder="{{ __('Email address') }}" />
                            </div>

                            <div class="col-lg-3 col-md-6">
                                <label><b>{{ __('Website') }}</b></label>
                                <input type="text" name="website" class="form-control" id="branch_website" data-next="branch_date_format" value="{{ $branch->website }}" placeholder="{{ __('Website Url') }}" />
                            </div>

                            @if ($branch->branch_type != \App\Enums\BranchType::ChainShop->value)
                                <div class="col-lg-6 col-md-6">
                                    <label><b>{{ __('Logo') }}</b> {{ __('Logo') }} <small class="text-danger" style="font-size: 9px;">{{ __('Req. size H:40px * W:100px') }}</small></label>

                                    <input type="file" name="logo" class="form-control " id="logo" @if ($branch?->logo) data-default-file="{{ file_link('branchLogo', $branch?->logo) }}" @endif />

                                    <span class="error error_branch_logo"></span>
                                    @if ($branch->logo)
                                        <a href="{{ route('branches.logo.delete', $branch->id) }}" class="btn btn-sm btn-danger mt-1" id="deleteBranchLogo">{{ __('Remove Store Logo') }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Date Format') }}</label>
                                <select name="date_format" class="form-control" id="branch_date_format" data-next="branch_time_format">
                                    <option value="d-m-Y" {{ $branchSettings['business_or_shop__date_format'] == 'd-m-Y' ? 'SELECTED' : '' }}>{{ date('d-m-Y') }}</option>
                                    <option value="m-d-Y" {{ $branchSettings['business_or_shop__date_format'] == 'm-d-Y' ? 'SELECTED' : '' }}>{{ date('m-d-Y') }}</option>
                                    <option value="Y-m-d" {{ $branchSettings['business_or_shop__date_format'] == 'Y-m-d' ? 'SELECTED' : '' }}>{{ date('Y-m-d') }}</option>
                                </select>
                                <span class="error error_date_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Format') }}</label>
                                <select name="time_format" class="form-control" id="branch_time_format" data-next="branch_timezone">
                                    <option {{ $branchSettings['business_or_shop__time_format'] == '12' ? 'SELECTED' : '' }} value="12">{{ __('12 Hour') }}</option>
                                    <option {{ $branchSettings['business_or_shop__time_format'] == '24' ? 'SELECTED' : '' }} value="24">{{ __('24 Hour') }}</option>
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Time Zone') }} <span class="text-danger">*</span> {{ now()->format('Y-m-d') }}</label>
                                <select required name="timezone" class="form-control" id="branch_timezone" data-next="branch_stock_accounting_method">
                                    <option value="">{{ __('Time Zone') }}</option>
                                    @foreach ($timezones as $key => $timezone)
                                        <option {{ ($branchSettings['business_or_shop__timezone'] ?? 'Asia/Dhaka') == $key ? 'SELECTED' : '' }} value="{{ $key }}">{{ $timezone }}</option>
                                    @endforeach
                                </select>
                                <span class="error error_time_format"></span>
                            </div>
                        </div>

                        @if ($branch->branch_type == \App\Enums\BranchType::DifferentShop->value)
                            <div class="row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="stock_accounting_method_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Stock Accounting Method') }}</label>
                                    <select name="stock_accounting_method" class="form-control" id="branch_stock_accounting_method" data-next="branch_account_start_date">
                                        @php
                                            $stockAccountingMethod = $generalSettings['business_or_shop__stock_accounting_method'] ?? null;
                                        @endphp
                                        @foreach (\App\Enums\StockAccountingMethod::cases() as $item)
                                            <option @selected($stockAccountingMethod == $item->value) value="{{ $item->value }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error error_financial_year_start"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="account_start_date_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Account Start Date') }} <span class="text-danger">*</span></label>
                                    @php
                                        $accountStartDate = $generalSettings['business_or_shop__account_start_date'] ?? null;
                                    @endphp
                                    <input type="text" name="account_start_date" class="form-control" id="branch_account_start_date" value="{{ $accountStartDate }}" data-next="branch_financial_year_start_month" autocomplete="off">
                                    <span class="error error_account_start_date"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-1 {{ $branch->branch_type == \App\Enums\BranchType::ChainShop->value ? 'd-hide' : '' }}" id="financial_year_start_month_field">
                                <div class="col-md-12">
                                    <label class="fw-bold">{{ __('Financial Year Start Month') }}</label>
                                    <div class="input-group">
                                        <select name="financial_year_start_month" id="branch_financial_year_start_month" class="form-control select2" data-next="branch_currency_id">
                                            @php
                                                $months = \App\Enums\Months::cases();
                                                $financialYearStartMonth = $branchSettings['business_or_shop__financial_year_start_month'] ?? null;
                                            @endphp
                                            @foreach ($months as $month)
                                                <option {{ $financialYearStartMonth == $month ? 'SELECTED' : '' }} value="{{ $month->value }}">{{ $month->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <span class="error error_financial_year_start_month"></span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label class="fw-bold">{{ __('Currency') }}</label>
                                <div class="input-group">
                                    <select required name="currency_id" class="form-control select2" id="branch_currency_id" data-next="branch_save_changes">
                                        @foreach ($currencies as $currency)
                                            <option data-currency_symbol="{{ $currency->symbol }}" {{ $branchSettings['business_or_shop__currency_id'] == $currency->id ? 'SELECTED' : '' }} value="{{ $currency->id }}">
                                                {{ $currency->country . ' - ' . $currency->currency . '(' . $currency->code . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="currency_symbol" id="branch_currency_symbol" value="{{ $branchSettings['business_or_shop__currency_symbol'] }}">
                                </div>
                                <span class="error error_currency_id"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-end mt-2">
                    <div class="btn-loading">
                        <button type="button" class="btn loading_button branch_loading_btn d-hide"><i class="fas fa-spinner"></i><span> {{ __('Loading') }}...</span></button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                        <button type="button" id="branch_save_changes" class="btn btn-sm btn-success branch_submit_button">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('setups.branches.ajax_view.js_partials.edit_js')
