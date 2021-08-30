<div class="modal-header">
    <h6 class="modal-title" id="exampleModalLabel">{{ $process->product->name.' '.($process->variant ? $process->variant->variant_name : '').' ('.($process->variant ? $process->variant->variant_code : $process->product->product_code).')' }}</h6>
    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
</div>
<div class="modal-body">
    <table class="display data_tbl data__table">
        <thead>
            <tr class="bg-primary">
                <th class="text-start text-white">Ingredient</th>
                <th class="text-start text-white">Quantity</th>
                <th class="text-start text-white">Wastage Percent</th>
                <th class="text-start text-white">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($process->ingredients as $ingredient)
                <tr>
                    <td class="text-start">
                        {{ $ingredient->product->name .' '.($ingredient->variant ? $ingredient->variant->variant_name : '') }}
                    </td>
                    <td class="text-start">{{ $ingredient->final_qty.' '.$ingredient->unit->name }}</td>
                    <td class="text-start">{{ $ingredient->wastage_percent.'%'  }}</td>
                    <td class="text-start">{{json_decode($generalSettings->business, true)['currency'].' '. $ingredient->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="display data_tbl data__table">
            <tr>
                <th colspan="3" class="text-end">Total Ingredients :</th>
                <th>{{json_decode($generalSettings->business, true)['currency'].' '.$process->total_ingredient_cost }}</th>
            </tr>
        </tfoot>
   </table>
    <br>
   <div class="row">
       <div class="col-6">
           <table class="">
               <tbody>
                   <tr>
                       <th class="text-start">Wastage : </th>
                       <td class="text-start"> {{ $process->wastage_percent.'%' }}</td>
                   </tr>
                   <tr>
                        <th class="text-start">Total Output Quantity : </th>
                        <td class="text-start"> {{ $process->total_output_qty.' '.$process->unit->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-start">Instructions : </th>
                        <td ></td>
                    </tr>
               </tbody>
           </table>
       </div>

       <div class="col-6">
        <table class="display data_tbl data__table">
            <tbody>
                <tr>
                    <th class="text-start">Extra : </th>
                    <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.$process->production_cost }}</td>
                </tr>
                <tr>
                     <th class="text-start">Total Cost: </th>
                     <td class="text-start"> {{ json_decode($generalSettings->business, true)['currency'].' '.$process->total_cost }}</td>
                 </tr>
            </tbody>
        </table>
    </div>
   </div>
</div>