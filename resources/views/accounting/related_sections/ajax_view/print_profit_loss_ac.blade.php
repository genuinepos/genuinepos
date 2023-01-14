<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4; margin-top: 0.8cm;margin-bottom: 35px; margin-left: 15px;margin-right: 15px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>

<div class="row">
    <div class="col-md-12 text-center">
        @if ($branch_id == '')

            <h5>{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
            <p><b>@lang('menu.all_business_location')</b></p>
        @elseif ($branch_id == 'NULL')

            <h5>{{ $generalSettings['business__shop_name'] }} (@lang('menu.head_office'))</h5>
            <p style="width: 60%; margin:0 auto;">{{ $generalSettings['business__address'] }}</p>
        @else

            @php
                $branch = DB::table('branches')
                    ->where('id', $branch_id)
                    ->select('name', 'branch_code', 'city', 'state', 'zip_code', 'country')
                    ->first();
            @endphp

            <h5>{{ $branch->name . ' ' . $branch->branch_code }}</h5>
            <p style="width: 60%; margin:0 auto;">{{ $branch->city.', '.$branch->state.', '.$branch->zip_code.', '.$branch->country }}</p>
        @endif

        @if ($fromDate && $toDate)

            <p><b>@lang('menu.date') :</b>
                {{ date($generalSettings['business__date_format'], strtotime($fromDate)) }}
                <b>@lang('menu.to')</b> {{ date($generalSettings['business__date_format'], strtotime($toDate)) }}
            </p>
        @endif
        <h6 style="margin-top: 10px;"><b>@lang('menu.profit_loss_account') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table modal-table table-sm table-bordered">
            <tbody>
                <tr>
                    <td class="text-start">
                    <em>@lang('menu.total_sale') :</em> 
                    </td>

                    <td class="text-start">
                    <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale']) }}</em> 
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.purchase_return') :</em> 
                    </td>

                    <td class="text-start">
                    <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase_return']) }}</em> 
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.total_purchase') : </em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_purchase']) }})</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.sale_return') : </em> 
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_return']) }})</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.direct_expense') :</em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_direct_expense']) }})</em>     
                    </td>
                </tr>

                @if ($generalSettings['addons__manufacturing'] == 1)
                    <tr>
                        <td class="text-start">
                        <em>@lang('menu.total_production_cost') :</em>  
                        </td>

                        <td class="text-start">
                            <em>(0.00)</em>     
                        </td>
                    </tr>
                @endif
                
                {{-- <tr>
                    <td class="text-start">
                    <em>@lang('menu.opening_stock') :</em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['opening_stock']) }})</em>     
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.closing_stock') :</em>  
                    </td>

                    <td class="text-start">
                        <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['closing_stock']) }}</em>     
                    </td>
                </tr> --}}

                <tr>
                    <th class="text-end">
                        <em>@lang('menu.gross_profit') :</em>   
                    </th>

                    <td class="text-start">
                        <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em></b>  
                    </td>
                </tr>

                {{-- Cash Flow from investing --}}
                <tr>
                    <th class="text-start" colspan="2">
                        <strong>@lang('menu.net_profit_loss_information') :</strong>
                    </th>
                </tr>

                <tr>
                    <td class="text-start">
                        <em>@lang('menu.gross_profit') :</em> 
                    </td>
                    <td class="text-start"><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['gross_profit']) }}</em> </td>
                </tr>

                <tr>
                    <td class="text-start">
                        <em>@lang('menu.total_stock_adjustment') :</em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted']) }})</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                        <em>@lang('menu.total_adjustment_recovered') :</em>  
                    </td>

                    <td class="text-start">
                        <em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_adjusted_recovered']) }}</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                        <em>@lang('menu.total_sale_order_tax') :</em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_sale_order_tax']) }})</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                    <em>@lang('menu.item_sold_individual_tax') :</em>  
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['individual_product_sale_tax']) }})</em>    
                    </td>
                </tr>

                <tr>
                    <td class="text-start">
                       <em>@lang('menu.indirect_expense') :</em>   
                    </td>

                    <td class="text-start">
                        <em>({{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['total_indirect_expense']) }})</em> 
                    </td>
                </tr> 
                
                <tr>
                    <th class="text-end">
                        <em>@lang('menu.net_profit') :</em>
                    </th>

                    <td class="text-start">
                        <b><em>{{ App\Utils\Converter::format_in_bdt( $netProfitLossAccount['net_profit']) }}</em> </b>  
                    </td>
                </tr> 
            </tbody>
        </table>
    </div>
</div>

@if (env('PRINT_SD_OTHERS') == 'true')
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date') {{ date('d-m-Y , h:iA') }}
    </small>
</div>