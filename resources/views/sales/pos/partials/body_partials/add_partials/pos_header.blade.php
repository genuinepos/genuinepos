<style>
    .search_item_area {position: relative;}
    .select_area {position: relative;background: #ffffff;box-sizing: border-box;position: absolute;width: 100%;z-index: 9999999;padding: 0;left: 0%;display: none;border: 1px solid var(--main-color);margin-top: 1px;border-radius: 0px;}
    .select_area ul {list-style: none;margin-bottom: 0;padding: 4px 4px;}
    .select_area ul li a {color: #464343;text-decoration: none;font-size: 12px;padding: 2px 3px;display: block;line-height: 15px;border: 1px solid #968e92;font-weight: 400;}
    .select_area ul li a:hover {background-color: #999396;color: #fff;}
    .selectProduct {background-color: #746e70!important;color: #fff !important;}
    .text-info {color: #0795a5 !important;}
    .pos-logo img {
        height: auto;
        width: 70%;
    }
</style>

<div class="head-pos">
    <input type="hidden" name="action" id="action" value="">
    <input type="text" class="d-hide" name="ex_sale_id" id="ex_sale_id" value="">
    <input type="hidden" name="cash_register_id" value="{{ $openedCashRegister->id }}">
    <input type="hidden" name="sale_account_id" value="{{ $openedCashRegister->sale_account_id }}">
    <input type="text" class="d-hide" name="button_type" id="button_type" value="0">
    <input type="hidden" id="store_url" value="{{ route('sales.pos.store') }}">
    <input type="hidden" id="exchange_url" value="{{ route('sales.pos.exchange.confirm') }}">
    <nav class="pos-navigation">
        <div class="col-lg-9 nav-left-sec">
            <div class="row g-1 align-items-center">
                <div class="col-xl-5 col-lg-3">
                    <div class="row g-1">
                        <div class="col-xl-4 logo-sec">
                            <div class="pos-logo d-flex justify-content-center">
                                @if (auth()->user()->branch)
                                    @if (auth()->user()->branch->logo != 'default.png')
                                        <img
                                        src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}">
                                        {{-- src="{{ asset(config('speeddigit.app_logo')) }}"> --}}
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{
                                        auth()->user()->branch->name }}</span>
                                    @endif
                                @else
                                    @if ($generalSettings['business__business_logo'] != null)
                                    <img
                                        {{-- src="{{ asset(config('speeddigit.app_logo'))}}" --}}
                                        src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}"
                                        alt="logo" class="logo__img">
                                    @else
                                        <span style="font-family: 'Anton', sans-serif;font-size:15px;color:white;">{{
                                        $generalSettings['business__shop_name'] }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-8 col-sm-12 col-12 address">
                            @if ($openedCashRegister->branch)
                                <p class="store-name">
                                    {{ $openedCashRegister->branch->name.'-'.$openedCashRegister->branch->branch_code }}
                                </p>
                                <p class="address-name">
                                    {{ $openedCashRegister->branch->city ? $openedCashRegister->branch->city.', ' : ''}}
                                    {{ $openedCashRegister->branch->state ? $openedCashRegister->branch->state.', ' : ''}}
                                    {{ $openedCashRegister->branch->country ? ', '.$openedCashRegister->branch->country : ''}}
                                </p>
                            @else
                                <p class="store-name">
                                    {{ $generalSettings['business__shop_name'] }}
                                </p>
                                <p class="address-name">
                                    {{ Str::limit($generalSettings['business__address'], 45) }}
                                </p>
                            @endif
                            <small class="login-user-name">
                                <span class="text-highlight">{{ __('Loggedin') }} </span> {{ auth()->user()->prefix.' '.auth()->user()->name.'
                                '.auth()->user()->last_name }}.
                                <span>
                                    <span class="text-highlight">{{ __('C.Register') }} </span>
                                    @if ($openedCashRegister->admin)
                                        @if ($openedCashRegister->admin->role_type == 1)
                                            Super-Admin.
                                        @elseif($openedCashRegister->admin->role_type == 2)
                                            Admin.
                                        @else
                                            {{ $openedCashRegister->admin?->roles()?->first()?->name }}.
                                        @endif
                                    @endif
                                </span>
                                <span> <span class="text-highlight">@lang('menu.cash_counter') </span> {{ $openedCashRegister->cash_counter ?
                                    $openedCashRegister->cash_counter->counter_name : 'N/A' }}.</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-9">
                    <div class="input-sec">
                        <div class="row g-1">
                            <div class="col-lg-6 col-12 sm-input-sec-w">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <select name="customer_id" class="form-control" id="customer_id">
                                        <option value="">{{ __('Walk-In-Customer') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name.' ('.$customer->phone.')'
                                            }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append add_button" id="addCustomer">
                                        <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                    </div>
                                </div>

                                <div class="search_item_area">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" name="search_product" class="form-control"
                                            id="search_product" placeholder="Scan/Search Items by SKU/Barcode" autofocus
                                            autocomplete="off">
                                        @if (auth()->user()->can('product_add'))
                                            <div class="input-group-append add_button" id="add_product">
                                                <span class="input-group-text"><i class="fas fa-plus"></i></span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="select_area">
                                        <ul id="list" class="variant_list_area"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 input-value-sec">
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">Reedem Point</span>
                                    </div>

                                    <input readonly type="number" step="any" class="form-control" name="earned_point" id="earned_point" tabindex="-1">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus"> = {{ $generalSettings['business__currency'] }}</span>
                                    </div>

                                    <input readonly type="text" class="form-control" id="trial_point_amount" tabindex="-1">
                                </div>


                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text valus">Current Stock</span>
                                    </div>
                                    <input readonly type="text" class="form-control" id="stock_quantity" tabindex="-1">

                                    <div class="input-group-prepend ms-1">
                                        <select name="price_group_id" class="form-control" id="price_group_id">
                                            <option value="">@lang('menu.default_selling_price')</option>
                                            @foreach ($priceGroups as $pg)
                                            <option value="{{ $pg->id }}">{{ $pg->name }}</option>
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
                        <a href="#" class="pos-btn status" id="fullscreen" title="Full Screen" tabindex="-1">
                            <i class="fas fa-expand"></i>
                        </a>

                        <a href="#" class="pos-btn position-relative" id="readDocument" title="Shortcut button list" tabindex="-1">
                            <i class="fas fa-file-alt"></i>
                            <div class="position-absolute doc">
                                <ul class="p-2 pt-3">
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F3</span>
                                        <span class="desc">View stock</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+Q</span>
                                        <span class="desc">Quick setup</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+C</span>
                                        <span class="desc">Copy Element</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">Ctrl+V</span>
                                        <span class="desc">Paste Copied</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                    <li>
                                        <span class="icon shortcut">F2</span>
                                        <span class="desc">Save as draft</span>
                                    </li>
                                </ul>
                            </div>
                        </a>

                        <a href="#" class="pos-btn status" id="suspends" title="Suspended Invoice" tabindex="-1">
                            <i class="fas text-warning fa-pause"></i>
                        </a>

                        <a href="#" class="pos-btn" data-bs-toggle="modal" data-bs-target="#calculatorModal" tabindex="-1">
                            <span class="fas fa-calculator"></span>
                        </a>

                        <a href="#" class="pos-btn" id="hard_reload" tabindex="-1">
                            <span class="fas fa-redo-alt"></span>
                        </a>

                        {{-- <a href="#" class="pos-btn d-hide d-md-block" id="hard_reload" title="Hard Reload">
                            <span class="fas fa-redo-alt"></span>
                        </a> --}}

                        <div class="modal" id="calculatorModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modail-body" id="calculator">
                                        <div class="calculator-bg">
                                            <div class="calculator-bg__main">
                                                <div class="calculator-bg__main__screen">
                                                    <div class="calculator-bg__main__screen__first"></div>
                                                    <div class="calculator-bg__main__screen__second">0</div>
                                                </div>
                                                <button class="calculator-bg__main__ac">AC</button>
                                                <button class="calculator-bg__main__del">DEL</button>
                                                <button class="calculator-bg__main__operator">/</button>
                                                <button class="calculator-bg__main__num">7</button>
                                                <button class="calculator-bg__main__num">8</button>
                                                <button class="calculator-bg__main__num">9</button>
                                                <button class="calculator-bg__main__operator">x</button>
                                                <button class="calculator-bg__main__num">4</button>
                                                <button class="calculator-bg__main__num">5</button>
                                                <button class="calculator-bg__main__num">6</button>
                                                <button class="calculator-bg__main__operator">+</button>
                                                <button class="calculator-bg__main__num">1</button>
                                                <button class="calculator-bg__main__num">2</button>
                                                <button class="calculator-bg__main__num">3</button>
                                                <button class="calculator-bg__main__operator">-</button>
                                                <button class="calculator-bg__main__num decimal">.</button>
                                                <button class="calculator-bg__main__num">0</button>
                                                <button class="calculator-bg__main__result">=</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->can('register_view'))
                            <a href="#" class="pos-btn text-info" id="cash_register_details" title="Register Details" tabindex="-1"><i
                                class="fas fa-cash-register"></i></a>
                        @endif

                        @if (auth()->user()->can('register_close'))
                            <a href="#" class="pos-btn text-danger" id="close_register" title="Close Register" tabindex="-1">
                                <span class="fas fa-times"></span></a>
                        @endif

                        <a href="#" class="pos-btn" tabindex="-1">
                            <span class="fas fa-bell"></span>
                        </a>

                        <a href="#" class="pos-btn" id="pos_exit_button" tabindex="-1">
                            <span class="fas fa-backward"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>