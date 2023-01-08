<link href="{{asset('backend/css/data-table.min.css')}}" rel="stylesheet" type="text/css">
<p>
    <b>@lang('menu.stock_location') :
    {!! auth()->user()->branch ? auth()->user()->branch->name.'/'.auth()->user()->branch->branch_code.' (BL)' : $generalSettings['business__shop_name'].'' !!}
    </b>
</p>
<table class="table modal-table table-sm table-striped" id="data_table">
    <thead>
        <tr class="bg-secondary">
            <th class="text-startx text-white">@lang('menu.serial')</th>
            <th class="text-startx text-white">@lang('menu.item')</th>
            <th class="text-startx text-white">@lang('menu.stock')</th>
            <th class="text-startx text-white">@lang('menu.unit')</th>
        </tr>
    </thead>
    <tbody>
        @if (count($products) > 0)
            @foreach ($products as $product)
                @if ($product->var_name)
                    <tr>
                        <td class="text-start">{{ $loop->index + 1 }}</td>
                        <td class="text-start">
                            {{ $product->pro_name. ' ('.$product->var_name.') ' }}
                            <small>({{$product->var_code}})</small>
                        </td>
                        <td class="text-start">{!! $product->variant_quantity !!}</td>
                        <td class="text-start">{{ $product->u_code }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="text-start">{{ $loop->index + 1 }}</td>
                        <td class="text-start">
                            {{ $product->pro_name }} <small>({{$product->pro_code}})</small>
                        </td>
                        <td class="text-start">{!! $product->product_quantity !!}</td>

                        <td class="text-start">{!! $product->u_code !!}</td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="3">@lang('menu.no_data_found')</td>
            </tr>
        @endif
    </tbody>
</table>
<style>
    #data_table_length{display: none!important;}
    .dataTables_wrapper {margin-top: 0px!important;}
    div#data_table_filter input {height: 23px!important;width: 68%!important;}
    .dataTables_info{display: none!important;}
</style>
<script src="{{asset('backend/js/data-table.jquery.min.js')}}"></script>
<script>
    $('#data_table').DataTable();
</script>

