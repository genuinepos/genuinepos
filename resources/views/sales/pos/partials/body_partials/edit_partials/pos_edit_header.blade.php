<style>
    .search_item_area {
        position: relative;
    }

    .select_area {
        position: relative;
        background: #ffffff;
        box-sizing: border-box;
        position: absolute;
        width: 100%;
        z-index: 9999999;
        padding: 0;
        left: 0%;
        display: none;
        border: 1px solid var(--main-color);
        margin-top: 1px;
        border-radius: 0px;
    }

    .select_area ul {
        list-style: none;
        margin-bottom: 0;
        padding: 4px 4px;
    }

    .select_area ul li a {
        color: #464343;
        text-decoration: none;
        font-size: 12px;
        padding: 2px 3px;
        display: block;
        line-height: 15px;
        border: 1px solid #968e92;
        font-weight: 400;
    }

    .select_area ul li a:hover {
        background-color: #999396;
        color: #fff;
    }

    .selectProduct {
        background-color: #746e70 !important;
        color: #fff !important;
    }

    .text-info {
        color: #0795a5 !important;
    }

    .pos-logo img {
        height: auto;
        width: 70%;
    }

    .select2-container .select2-selection--single {
        height: 26px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        width: 233px;
    }
</style>

<div class="head-pos">
    <input type="hidden" name="status" id="status">
    <input type="hidden" name="sale_screen_type" id="sale_screen_type" value="{{ $saleScreenType }}">
    <input type="hidden" name="cash_register_id" value="{{ $openedCashRegister->id }}">

    @if (isset($sale->jobCard))
        <input type="text" class="d-hide" name="job_card_id" id="job_card_id" value="{{ $sale?->jobCard?->id }}">
    @endif

    <nav class="pos-navigation">
        <div class="col-lg-9 nav-left-sec">
            <div class="row g-1 align-items-center">
                <div class="col-xl-5 col-lg-3">
                    <div class="row g-1">
                        <div class="col-xl-4 logo-sec">
                            <div class="pos-logo d-flex justify-content-center">
                                @if (auth()->user()?->branch)
                                    @if (auth()->user()?->branch?->parent_branch_id)

                                        @if (auth()->user()?->branch?->parentBranch?->logo)
                                            <img style="height: 45px; width:200px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->parentBranch?->logo) }}">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ auth()->user()?->branch?->parentBranch?->name }}</span>
                                        @endif
                                    @else
                                        @if (auth()->user()?->branch?->logo)
                                            <img style="height: 45px; width:200px;" src="{{ file_link('branchLogo', auth()->user()?->branch?->logo) }}">
                                        @else
                                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ auth()->user()?->branch?->name }}</span>
                                        @endif
                                    @endif
                                @else
                                    @if ($generalSettings['business_or_shop__business_logo'] != null)
                                        <img style="height: 45px; width:200px;" src="{{ file_link('businessLogo', $generalSettings['business_or_shop__business_logo']) }}" alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{ $generalSettings['business_or_shop__business_name'] }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-sm-12 col-12 address">
                            @if ($openedCashRegister?->branch_id)
                                @if ($openedCashRegister?->branch->parent_branch_id)
                                    <p class="store-name">{{ $openedCashRegister?->branch->parentBranch?->name }}</p>
                                @else
                                    <p class="store-name">{{ $openedCashRegister?->branch?->name }}</p>
                                @endif

                                <p class="address-name">
                                    {{ $openedCashRegister?->branch?->city ? $openedCashRegister->branch->city . ', ' : '' }}
                                    {{ $openedCashRegister?->branch?->state ? $openedCashRegister->branch->state . ', ' : '' }}
                                    {{ $openedCashRegister?->branch?->country ? ', ' . $openedCashRegister->branch->country : '' }}
                                </p>
                            @else
                                <p class="store-name">
                                    {{ $generalSettings['business_or_shop__business_name'] }}
                                </p>

                                <p class="address-name">
                                    {{ Str::limit($generalSettings['business_or_shop__address'], 45) }}
                                </p>
                            @endif

                            <small class="login-user-name">
                                <span class="fw-bold">{{ __('C.Register') }}: </span>
                                @if ($openedCashRegister->user)
                                    @if ($openedCashRegister->user->role_type == 1)
                                        {{ __('SuperAdmin.') }}
                                    @elseif($openedCashRegister->user->role_type == 2)
                                        {{ __('Admin.') }}
                                    @else
                                        {{ $openedCashRegister->user?->roles()?->first()?->name }}.
                                    @endif
                                @endif
                                </span>
                                <span> <span class="fw-bold">{{ __('Cash Counter') }}: </span> {{ $openedCashRegister->cashCounter ? $openedCashRegister->cashCounter->counter_name : 'N/A' }}.</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-9">
                    <div class="input-sec">
                        <div class="row g-1">
                            <div class="col-lg-6 col-12 sm-input-sec-w">
                                <div class="input-group flex-nowrap mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <select name="customer_account_id" class="form-control select2" id="customer_account_id" data-next="status">
                                        @foreach ($customerAccounts as $customerAccount)
                                            @php
                                                $accountType = $customerAccount->sub_sub_group_number == 6 ? '' : ' -(' . __('Supplier') . ')';
                                            @endphp
                                            <option @selected($sale->customer_account_id == $customerAccount->id) data-default_balance_type="{{ $customerAccount->default_balance_type }}" data-sub_sub_group_number="{{ $customerAccount->sub_sub_group_number }}" data-pay_term="{{ $customerAccount->pay_term }}" data-pay_term_number="{{ $customerAccount->pay_term_number }}" value="{{ $customerAccount->id }}">{{ $customerAccount->name . '/' . $customerAccount->phone . $accountType }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text {{ $generalSettings['subscription']->features['contacts'] == 0 || !auth()->user()->can('customer_add') ? 'disabled_element' : '' }} add_button" id="{{ $generalSettings['subscription']->features['contacts'] == 1 && auth()->user()->can('customer_add') ? 'addContact' : '' }}"><i class="fas fa-plus-square text-dark"></i></span>
                                    </div>
                                </div>

                                <div class="search_item_area">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control" id="search_product" placeholder="{{ __('Scan/Search Items by SKU/Barcode') }}" autofocus autocomplete="off">
                                        <div class="input-group-append add_button">
                                            <span class="input-group-text {{ $generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::False->value || !auth()->user()->can('product_add') ? 'disabled_element' : '' }} add_button" id="{{ $generalSettings['subscription']->features['inventory'] == \App\Enums\BooleanType::True->value && auth()->user()->can('product_add') ? 'addProduct' : '' }}"><i class="fas fa-plus-square text-dark input_i"></i></span>
                                        </div>
                                    </div>

                                    <div class="select_area">
                                        <ul id="list" class="variant_list_area"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 input-value-sec">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">{{ __('Reedem Point') }}</span>
                                    </div>

                                    <input readonly type="number" step="any" class="form-control" name="earned_point" id="earned_point" tabindex="-1">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus"> = {{ $generalSettings['business_or_shop__currency_symbol'] }}</span>
                                    </div>

                                    <input readonly type="text" class="form-control" id="trial_point_amount" tabindex="-1">
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">{{ __('Curr. Stock') }}</span>
                                    </div>
                                    <input readonly type="text" class="form-control" id="stock_quantity" tabindex="-1">

                                    <div class="input-group-prepend ms-1">
                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                            <option value="">{{ __('Default Price Group') }}</option>
                                            @foreach ($priceGroups as $pg)
                                                <option {{ isset($generalSettings['pos__default_price_group_id']) && $generalSettings['pos__default_price_group_id'] == $pg->id ? 'SELECTED' : '' }} value="{{ $pg->id }}">{{ $pg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-9 col-12 input-buttob-sec">
            <div class="input-section">
                <div class="btn-section">
                    <div class="date">
                        <p>{{ date('d-m-Y') }} <span id="time">6:58 AM</span></p>
                    </div>

                    <div class="btn-sec">
                        <a href="{{ route('sales.helper.suspended.modal', 20) }}" class="pos-btn status" id="suspendedInvoiceBtn" title="Suspended Invoice" tabindex="-1">
                            <i class="fas text-warning fa-pause"></i>
                        </a>
                        <a href="#" class="pos-btn mr-1" data-bs-toggle="modal" data-bs-target="#calculatorModal" tabindex="-1">
                            <span class="fas fa-calculator"></span>
                        </a>
                        @if (auth()->user()->can('register_view'))
                            <a href="{{ route('cash.register.show', $openedCashRegister->id) }}" class="pos-btn text-info" id="cashRegisterDetailsBtn" title="{{ __('Cash Register Details') }}" tabindex="-1"><i class="fas fa-cash-register"></i></a>
                        @endif

                        @if (auth()->user()->can('register_close'))
                            <a href="{{ route('cash.register.close', $openedCashRegister->id) }}" class="pos-btn text-danger" id="closeCashRegisterBtn" title="{{ __('Close Register') }}" tabindex="-1"> <span class="fas fa-times"></span></a>
                        @endif
                        <a href="#" class="pos-btn" tabindex="-1"><span class="fas fa-bell"></span></a>
                        <a href="#" class="pos-btn" id="pos_exit_button" tabindex="-1"><span class="fas fa-backward"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
