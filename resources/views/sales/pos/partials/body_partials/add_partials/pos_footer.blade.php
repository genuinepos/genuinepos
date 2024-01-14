<div class="row mt-1 d-lg-flex d-hide">
    <div class="col-12">
        <div class="pos-footer">
            <div class="logo_wrapper d-block w-100 text-center">
                <img src="{{ asset(config('speeddigit.app_logo')) }}" style="max-width: 100%; height: 20px; width: auto;margin-top: 12px;">
            </div>

            @if ($generalSettings['pos__is_show_recent_transactions'] == '1')
                <div class="pos-foot-con d-inline-block position-absolute" style="right: 15%; top: 60%; transform: translateY(-41%)">
                    <div class="input-group">
                        <label class="pe-1 fw-bold">{{ __('Print') }}</label>
                        <select name="print_page_size" id="print_page_size" class="form-control print_page_size">
                            @foreach (\App\Enums\PrintPageSize::cases() as $item)
                                <option {{ $generalSettings['print_page_size__pos_sale_page_size'] == $item->value ? 'SELECTED' : '' }} value="{{ $item->value }}">{{ App\Services\PrintPageSizeService::pageSizeName($item->value, false) }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="pos-foot-con d-inline-block position-absolute" style="right: -10px; top: 50%; transform: translateY(-41%)">
                    <a href="{{ route('sales.helper.recent.transaction.modal', ['initialStatus' => App\Enums\SaleStatus::Final->value, 'saleScreenType' => App\Enums\SaleScreenType::PosSale->value, 'limit' => 20]) }}" class="btn btn-sm btn-primary resent-tn h-auto py-1" id="recentTransactionsBtn" tabindex="-1">{{ __('Recent Transactions') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- <div class="sub_total" id="footer_fixed">
    <div class="sub-total-input">
        <div class="row">
            <div class="col-5">
                <div class="row">
                    <label class="col-sm-4 col-form-label text-white">@lang('menu.total_qty')</label>
                    <div class="col-sm-8 ">
                        <input type="text" value="800" class="form-control mb_total_qty" disabled>
                    </div>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <label class="col-sm-4 col-form-label text-white">@lang('menu.total_item')</label>
                    <div class="col-sm-8 ">
                        <input type="text" value="800" class="form-control mb_total_item" disabled>
                    </div>
                </div>
            </div>

            <div class="col-2 text-center">
                <div class="footer_trasc_btn">
                    @if ($generalSettings['pos__is_show_recent_transactions'] == '1')
                        <a href="#" class="resent-tn" tabindex="-1"><span class="fas fa-clock"></span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> --}}
<script src="{{ asset('backend/asset/js/SimpleCalculadorajQuery.js') }}" defer></script>
