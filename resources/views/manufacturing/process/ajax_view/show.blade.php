<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">{{ $process->product->name.' '.($process->variant ? $process->variant->variant_name : '').' ('.($process->variant ? $process->variant->variant_code : $process->product->product_code).')' }}</h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <div class="table-responsive">
        <table class="display data_tbl data__table">
            <thead>
                <tr class="bg-secondary">
                    <th class="text-start text-white">@lang('menu.ingredients')</th>
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
                        <th class="text-start">@lang('menu.wastage') </th>
                        <td class="text-start"> {{ $process->wastage_percent.'%' }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">@lang('menu.total_output_quantity') </th>
                        <td class="text-start"> {{ $process->total_output_qty.' '.$process->unit->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">@lang('menu.instructions') </th>
                        <td ></td>
                    </tr>
               </tbody>
           </table>
       </div>

        <div class="col-sm-6">
            <table class="display data_tbl data__table">
                <tbody>
                    <tr>
                        <th class="text-start">@lang('menu.additional_cost') </th>
                        <td class="text-start"> {{ $generalSettings['business__currency'].' '.$process->production_cost }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">@lang('menu.total_cost') </th>
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
