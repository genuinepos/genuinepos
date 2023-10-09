@php
    $timeFormat = $generalSettings['business__time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" id="view-modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ __("Process/Bill Of Materials") }}</h6>
                <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-unstyled">
                            <li style="font-size:11px!important;"><strong>{{ __("Product Name") }} : </strong>
                                {{ $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')' }}
                            </li>

                            <li style="font-size:11px!important;"><strong>{{ __("Created From") }} : </strong>
                                @if ($process->branch_id)

                                    @if($process?->branch?->parentBranch)

                                        {{ $process?->branch?->parentBranch?->name . '(' . $process?->branch?->area_name . ')'.'-('.$process?->branch?->branch_code.')' }}
                                    @else

                                        {{ $process?->branch?->name . '(' . $process?->branch?->area_name . ')'.'-('.$process?->branch?->branch_code.')' }}
                                    @endif
                                @else

                                    {{ $generalSettings['business__shop_name'].'(Business)' }}
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table modal-table table-sm">
                        <thead>
                            <tr class="bg-secondary">
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Ingredient') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                                <th class="text-white text-start fw-bold" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($process->ingredients as $ingredient)
                                <tr>
                                    <td class="text-start" style="font-size:11px!important;">
                                        {{ $ingredient->product->name . ' ' . $ingredient?->variant?->variant_name }}
                                    </td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->name }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                                    <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="display data_tbl data__table">
                            <tr>
                                <th colspan="5" class="text-end">{{ __('Total Ingredients') }} : {{ $generalSettings['business__currency'] }}</th>
                                <th class="text-start">{{ App\Utils\Converter::format_in_bdt($process->total_ingredient_cost) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <th class="text-end">{{ __("Total Output Qty") }} : </th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->total_output_qty) . '/' . $process->unit->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">{{ __("Instructions") }} :</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="display table modal-table table-sm">
                                <tbody>
                                    <tr>
                                        <th class="text-end">{{ __('Addl. Production Cost') }} : {{ $generalSettings['business__currency'] }}</th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->additional_production_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">{{ __('Net Cost') }} : {{ $generalSettings['business__currency'] }}</th>
                                        <td class="text-end"> {{ App\Utils\Converter::format_in_bdt($process->net_cost) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('manufacturing.process.edit', [$process->id]) }}" class="btn btn-sm btn-secondary">@lang('menu.edit')</a>
                        <button type="submit" class="btn btn-sm btn-success" id="modalDetailsPrintBtn">{{ __('Print') }}</button>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    div#footer {position:fixed;bottom:0px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}
</style>
<!-- Print Template-->
<div class="print_modal_details d-hide">
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black; padding-botton: 3px;">
            <div class="col-4">
                @if ($generalSettings['business__business_logo'] != null)

                    <img src="{{ asset('uploads/business_logo/' . $generalSettings['business__business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ $generalSettings['business__shop_name'] }}</span>
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;" class="p-0 m-0">
                    <strong>{{ $generalSettings['business__shop_name'] }}</strong>
                </p>

                <p>{{ $generalSettings['business__address'] }}</p>

                <p>
                    @php
                        $email = $generalSettings['business__email'];
                        $phone = $generalSettings['business__phone'];
                    @endphp

                    <strong>{{ __("Email") }} : </strong> {{ $email }},
                    <strong>{{ __("Phone") }} : </strong> {{ $phone }}
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h5 style="text-transform: uppercase;"><strong>{{ __("Bill Of Materials") }}</strong></h5>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>{{ __("Product Name") }} : </strong>
                        {{ $process->product->name . ' ' . ($process->variant ? $process->variant->variant_name : '') . ' (' . ($process->variant ? $process->variant->variant_code : $process->product->product_code) . ')' }}
                    </li>

                    <li style="font-size:11px!important;"><strong>{{ __("Created From") }} : </strong>
                        @if ($process->branch_id)

                            @if($process?->branch?->parentBranch)

                                {{ $process?->branch?->parentBranch?->name . '(' . $process?->branch?->area_name . ')'.'-('.$process?->branch?->branch_code.')' }}
                            @else

                                {{ $process?->branch?->name . '(' . $process?->branch?->area_name . ')'.'-('.$process?->branch?->branch_code.')' }}
                            @endif
                        @else

                            {{ $generalSettings['business__shop_name'].'(Business)' }}
                        @endif
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table pt-3 pb-3">
            <table class="table modal-table table-sm table-bordered">
                <thead>
                    <tr>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Ingredient') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Quantity') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Exc. Tax') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Vat/Tax') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Unit Cost Inc. Tax') }}</th>
                        <th class="fw-bold text-start" style="font-size:11px!important;">{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($process->ingredients as $ingredient)
                        <tr>
                            <td class="text-start" style="font-size:11px!important;">
                                {{ $ingredient->product->name . ' ' . $ingredient?->variant?->variant_name }}
                            </td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->final_qty) . '/' . $ingredient?->unit?->name }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_exc_tax) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ '(' . $ingredient->unit_tax_percent . '%)=' . App\Utils\Converter::format_in_bdt($ingredient->unit_tax_amount) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->unit_cost_inc_tax) }}</td>
                            <td class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($ingredient->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end" style="font-size:11px!important;">{{ __('Total Ingredients') }} : {{ $generalSettings['business__currency'] }}</th>
                        <th class="text-start" style="font-size:11px!important;">{{ App\Utils\Converter::format_in_bdt($process->total_ingredient_cost) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div><br>

        <div class="row">
            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tr>
                        <th class="text-start" style="font-size:11px!important;">{{ __("Total Output Quantity") }} : </th>
                        <td class="text-start" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->total_output_qty) . '/' . $process->unit->name }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-end" style="font-size:11px!important;">{{ __('Addl. Production Cost') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->additional_production_cost) }}</td>
                        </tr>
                        <tr>
                            <th class="text-end" style="font-size:11px!important;">{{ __('Net Cost') }} : {{ $generalSettings['business__currency'] }}</th>
                            <td class="text-end" style="font-size:11px!important;"> {{ App\Utils\Converter::format_in_bdt($process->net_cost) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div><br><br>

        <div class="row">
            <div class="col-6">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __("Prepared By") }}</strong></p>
                </div>
            </div>

            <div class="col-6">
                <div class="details_area text-center">
                    <p class="text-uppercase borderTop"><strong>{{ __("Authorized By") }}</strong></p>
                </div>
            </div>
        </div>

        <div id="footer">
            <div class="row mt-1">
                <div class="col-4 text-start">
                    <small style="font-size: 9px!important;">{{ __("Print Date") }} : {{ date($generalSettings['business__date_format']) }}</small>
                </div>

                <div class="col-4 text-center">
                    @if (env('PRINT_SD_SALE') == true)
                        <small style="font-size: 9px!important;" class="d-block">{{ __("Powered By") }} <strong>@lang('SpeedDigit Software Solution').</strong></small>
                    @endif
                </div>

                <div class="col-4 text-end">
                    <small style="font-size: 9px!important;">{{ __("Print Time") }} : {{ date($timeFormat) }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Print Template End-->
