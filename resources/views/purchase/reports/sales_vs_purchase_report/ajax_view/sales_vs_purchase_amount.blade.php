<div class="sales_vs_purchase_amount_area">
    <div class="row g-3">
        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body card-custom">
                    <div class="heading">
                        <h6 class="text-primary"><b>{{ __("Purchase") }}</b> </h6>
                    </div>

                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="fw-bold">{{ __("Total Purchase") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalPurchaseIncludedTax']) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="fw-bold">{{ __("Total Purchase Return") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalPurchaseReturn']) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="fw-bold">{{ __("Total Purchase Included Return") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalPurchaseIncludedReturn']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="heading">
                        <h6 class="text-primary"><b>{{ __("Sales") }}</b></h6>
                    </div>

                    <table class="table modal-table table-sm">
                        <tbody>
                            <tr>
                                <th class="fw-bold">{{ __("Total Sales") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalSaleIncludedTax']) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="fw-bold">{{ __("Total Sales Return") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalSalesReturn']) }}
                                </td>
                            </tr>

                            <tr>
                                <th class="fw-bold">{{ __("Total Sales Included Return") }}</th>
                                <td>
                                    {{ $generalSettings['business_or_shop__currency_symbol'] }}
                                    {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalSaleIncludedReturn']) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="sales_vs_purchase_compare_area">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body card-custom">
                    <div class="heading">
                        <h6 class="text-navy-blue">{{ __("Overall (Total Sales - Total Sales Return - Total Purchase - Total Purchase Return)") }}</h6>
                    </div>

                    <div class="compare_area mt-3">
                        <p class="text-muted">{{ __("Total Sales Included Return ") }} (-) {{ __("Total Purchase Included Return") }}:
                            <span>
                                ({{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalSaleIncludedReturn']) }} - {{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['totalPurchaseIncludedReturn']) }}) =
                                <span class="{{ $salesVsPurchaseAmounts['saleMinusPurchase'] < 0 ? 'text-danger' : '' }}">{{ App\Utils\Converter::format_in_bdt($salesVsPurchaseAmounts['saleMinusPurchase']) }}</span>
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
