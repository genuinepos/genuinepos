<style>
    .dataTables_wrapper {
        margin-top: 0px !important;
    }

    .dataTables_wrapper {
        margin-top: 0px !important;
    }
</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="data_preloader mt-5" id="stock_preloader">
            <h6><i class="fas fa-spinner text-primary"></i> {{ __('Processing') }}</h6>
        </div>
        <div class="modal-header">
            <h6 class="modal-title">
                {{ __('Product Stock') }} |
                <b>
                    {{ location_label() }} :
                    @if (auth()->user()?->branch)

                        @if (auth()->user()?->branch?->parent_branch_id)
                            {{ auth()->user()?->branch?->parentBranch?->name }}
                        @else
                            {{ auth()->user()?->branch?->name }}
                        @endif
                    @else
                        {{ $generalSettings['business_or_shop__business_name'] }} ({{ __('Company') }})
                    @endif
                </b>
            </h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="stock_modal_body">
            <table class="display data_tbl data__table" id="data_table">
                <thead>
                    <tr class="bg-secondary">
                        <th class="text-startx text-white">{{ __('Serial') }}</th>
                        <th class="text-startx text-white">{{ __('Product') }}</th>
                        <th class="text-startx text-white">{{ __('Product Code') }}</th>
                        <th class="text-startx text-white">{{ __('Current Stock') }}</th>
                        <th class="text-startx text-white">{{ __('Unit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($productStocks['productBranchStock']) > 0)
                        @foreach ($productStocks['productBranchStock'] as $productBranchStock)
                            @php
                                $variantName = $productBranchStock->variant_name ? ' - ' . $productBranchStock->variant_name : '';
                                $productCode = $productBranchStock->variant_code ? $productBranchStock->variant_code : $productBranchStock->product_code;
                            @endphp
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}</td>
                                <td class="text-start">
                                    {{ $productBranchStock->product_name . $variantName }}
                                </td>
                                <td class="text-start">{{ $productCode }}</td>

                                @if ($productBranchStock->variant_id)
                                    <td class="text-start">{{ App\Utils\Converter::format_in_bdt($productBranchStock->variant__stock) }}</td>
                                @else
                                    <td class="text-start">{{ App\Utils\Converter::format_in_bdt($productBranchStock->product__stock) }}</td>
                                @endif

                                <td class="text-start">{{ $productBranchStock->unit_name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">{{ __('Data Not Found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- <style>
    #data_table_length{display: none!important;}
    .dataTables_wrapper {margin-top: 0px!important;}
    div#data_table_filter input {height: 23px!important;width: 68%!important;}
    .dataTables_info{display: none!important;}
</style> --}}
<script>
    $('#data_table').DataTable();
</script>
