<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">{{ $process->product->name.' '.($process->variant ? $process->variant->variant_name : '').' ('.($process->variant ? $process->variant->variant_code : $process->product->product_code).')' }}</h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <div class="table-responsive">
        <table class="display data_tbl data__table">
            <thead>
                <tr class="bg-secondary">
                    <th class="text-start text-white">@lang('menu.ingredients') </th>
                    <th class="text-start text-white">@lang('menu.quantity')</th>
                    <th class="text-start text-white">{{ __('Cost Inc.Tax') }}({{ $generalSettings['business__currency'] }})</th>
                    <th class="text-start text-white">@lang('menu.subtotal')({{ $generalSettings['business__currency'] }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($process->ingredients as $ingredient)
                    <tr>
                        <td class="text-start">
                            {{ $ingredient->product->name .' '.($ingredient->variant ? $ingredient->variant->variant_name : '') }}
                        </td>
                        <td class="text-start">{{ $ingredient->final_qty.' '.$ingredient->unit->name }}</td>
                        <td class="text-start">{{ $ingredient->unit_cost_inc_tax }}</td>
                        <td class="text-start">{{ $ingredient->subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="display data_tbl data__table">
                <tr>
                    <th colspan="3" class="text-end">{{ __('Total Ingredients') }} : {{$generalSettings['business__currency'] }}</th>
                    <th>{{ $process->total_ingredient_cost }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <br>
   <div class="row">
       <div class="col-sm-6">
           <table class="">
               <tbody>
                    <tr>
                        <th class="text-start">@lang('menu.wastage') : </th>
                        <td class="text-start"> {{ $process->wastage_percent.'%' }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">@lang('menu.total_output_quantity') : </th>
                        <td class="text-start"> {{ $process->total_output_qty.' '.$process->unit->name }}</td>
                    </tr>
                    <tr>
                        {{-- <th class="text-start">@lang('menu.instructions') </th>
                        <td ></td> --}}
                    </tr>
               </tbody>
           </table>
       </div>

        <div class="col-sm-6">
            <table class="display data_tbl data__table">
                <tbody>
                    <tr>
                        <th class="text-start">@lang('menu.additional_cost') : </th>
                        <td class="text-start"> {{ $generalSettings['business__currency'].' '.$process->production_cost }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">@lang('menu.total_cost') : </th>
                        <td class="text-start"> {{ $generalSettings['business__currency'].' '.$process->total_cost }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
   </div>
</div>

<div class="modal-footer">
    <div class="row">
        <div class="col-md-12">
            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
            <button type="submit" class="btn btn-sm btn-success print_btn">@lang('menu.print')</button>
        </div>
    </div>
 </div>
<!-- Print Template-->
<div class="transfer_print_template d-hide">
    <div class="details_area">
        <div class="heading_area">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-lg-12">
                    <div class="heading text-center">
                        <h5 class="company_name">{{ $generalSettings['business__shop_name'] }}</h5>

                        <h6 class="bill_name">{{ __('Processes Ingredients Details') }}</h6>

                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.address') : </strong>
                            @if ($process->branch)
                                <li><strong>@lang('menu.address') : </strong>
                                    {{ $process->branch->city }},
                                    {{ $process->branch->state }},
                                    {{ $process->branch->zip_code }},
                                    {{ $process->branch->country }}.
                                </li>
                            @else
                                {{ $generalSettings['business__address'] }}
                            @endif
                            <li><strong>@lang('menu.phone') : </strong> {{ $process->branch ? $process->branch->phone : $generalSettings['business__phone'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="sale_product_table pt-3 pb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-start">@lang('menu.sl') </th>
                        <th class="text-start">@lang('menu.ingredients') </th>
                        <th class="text-start">@lang('menu.quantity')</th>
                        <th class="text-start">{{ __('Cost Inc.Tax') }}({{ $generalSettings['business__currency'] }})</th>
                        <th class="text-start">@lang('menu.subtotal')({{ $generalSettings['business__currency'] }})</th>
                    </tr>
                </thead>
                <tbody class="transfer_print_product_list">
                    @foreach ($process->ingredients as $ingredient)
                        <tr>
                            <td><strong>{{ $loop->index +1 }}</strong></td>
                            <td class="text-start"><strong>
                                {{ $ingredient->product->name .' '.($ingredient->variant ? $ingredient->variant->variant_name : '') }}
                            </strong>
                            </td>
                            <td class="text-start"><strong>{{ $ingredient->final_qty.' '.$ingredient->unit->name }}</strong></td>
                            <td class="text-start"><strong>{{ $ingredient->unit_cost_inc_tax }} </strong></td>
                            <td class="text-start"><strong>{{ $ingredient->subtotal }} </strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="display data_tbl data__table">
                    <tr>
                        <th colspan="4" class="text-right">{{ __('Total Ingredients') }} : {{$generalSettings['business__currency'] }}</th>
                        <th><strong>{{ $process->total_ingredient_cost }}</strong></th>
                    </tr>
                </tfoot>
            </table>
        </div><br>
        <div class="row">
            <div class="col-md-6">
                <table class="display data_tbl data__table">
                    <tr>
                        <th class="text-end">@lang('menu.wastage') : </th>
                        <td class="text-start"> {{ $process->wastage_percent.'%' }}</td>
                    </tr>
                    <tr>
                        <th class="text-end">@lang('menu.total_output_quantity') : </th>
                        <td class="text-start"> {{ $process->total_output_qty.' '.$process->unit->name }}</td>
                    </tr>
                </table>
            </div>

             <div class="col-md-6">
                 <table class="display data_tbl data__table">
                        <tr>
                            <th class="text-end">@lang('menu.additional_cost') : </th>
                            <td class="text-start"> {{ $generalSettings['business__currency'].' '.$process->production_cost }}</td>
                        </tr>
                        <tr>
                            <th class="text-end">@lang('menu.total_cost') : </th>
                            <td class="text-start"> {{ $generalSettings['business__currency'].' '.$process->total_cost }}</td>
                        </tr>
                 </table>
             </div>
        </div><br><br>

        <div class="row">
            <div class="col-md-6">
                <p><strong>{{ __('Receivers signature') }}</strong></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>@lang('menu.signature_of_authority')</strong></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                {{-- <img style="width: 170px; height:20px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($transfer->invoice_id, $generator::TYPE_CODE_128)) }}"> --}}
                {{-- <p class="p-0 m-0"><b>{{ $transfer->invoice_id }}</b></p> --}}
                @if (env('PRINT_SD_OTHERS') == true)
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd').</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Print Template End-->
