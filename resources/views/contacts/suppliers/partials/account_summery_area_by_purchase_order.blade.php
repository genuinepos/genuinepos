<div class="account_summary_area">
    <div class="heading">
        <h5 class="py-1 pl-1 text-center">@lang('menu.account_summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <td class="text-end"><strong>@lang('menu.opening_balance') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end opening_balance" id="purchase_order_opening_balance"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_purchase') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_purchase" id="purchase_order_total_purchase"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_paid') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end text-success total_paid" id="purchase_order_total_paid"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_return') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_return" id="purchase_order_total_return"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.total_less') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_less" id="purchase_order_total_less"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>@lang('menu.balance_due') : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end text-danger total_purchase_due" id="purchase_order_total_purchase_due"></td>
                </tr>

                <tr>
                    <td class="text-end"><strong>{{ __('Total Returnable/Refundable Amount') }} : {{ $generalSettings['business']['currency'] }}</strong></td>
                    <td class="text-end total_purchase_return_due" id="purchase_order_total_purchase_return_due"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
