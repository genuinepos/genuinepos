<style>
    #recent_trans_preloader h6 {
        margin: auto;
    }
</style>
@php
    $__date_format = str_replace('-', '/', $generalSettings['business_or_shop__date_format']);
@endphp
<div class="modal-dialog col-50-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Recent Transactions') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="tab_list_area">
                <div class="btn-group">
                    <a href="{{ route('sales.helper.recent.transaction.sales', ['status' => App\Enums\SaleStatus::Final->value, 'saleScreenType' => $saleScreenType, 'limit' => 20]) }}" onclick="saleRecentTransactions(this); return false;" class="btn btn-sm btn-primary tab_btn tab_active" id="tab_btn"><i class="fas fa-info-circle"></i> {{ __('Finals') }}</a>

                    <a href="{{ route('sales.helper.recent.transaction.sales', ['status' => App\Enums\SaleStatus::Quotation->value, 'saleScreenType' => $saleScreenType, 'limit' => 20]) }}" onclick="saleRecentTransactions(this); return false;" class="btn btn-sm btn-primary tab_btn" id="tab_btn"><i class="fas fa-scroll"></i> {{ __('Quotations') }}</a>

                    <a href="{{ route('sales.helper.recent.transaction.sales', ['status' => App\Enums\SaleStatus::Draft->value, 'saleScreenType' => $saleScreenType, 'limit' => 20]) }}" onclick="saleRecentTransactions(this); return false;" class="btn btn-sm btn-primary tab_btn" id="tab_btn"><i class="fas fa-shopping-bag"></i> {{ __('Drafts') }}</a>
                </div>
            </div>

            <div class="tab_contant">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table_area" style="position: relative;">
                            <div class="data_preloader" id="recent_trans_preloader">
                                <h6><i class="fas fa-spinner"></i> {{ __('Processing') }}...</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="display modal-table table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __('S/L') }}</th>
                                            <th class="text-start">{{ __('Invoice ID') }}</th>
                                            <th class="text-start">{{ __('Date') }}</th>
                                            <th class="text-start">{{ __('Customer') }}</th>
                                            <th class="text-start">{{ __('Total Amount') }}</th>
                                            <th class="text-start">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="data-list" id="recent_transection_list">
                                        @if (count($sales))
                                            @foreach ($sales as $sale)
                                                @php
                                                    $branchName = '';
                                                    if ($sale->branch_id) {

                                                        if ($sale?->parent_branch_name) {

                                                            $branchName = $sale?->parent_branch_name . '(' . $sale?->branch_area_name . ')' . '-(' . $sale?->branch_code . ')';
                                                        } else {

                                                            $branchName = $sale?->branch_name . '(' . $sale?->branch_area_name . ')' . '-(' . $sale?->branch_code . ')';
                                                        }
                                                    } else {

                                                        $branchName = $generalSettings['business_or_shop__business_name'];
                                                    }
                                                @endphp
                                                <tr>
                                                    <td class="text-start fw-bold">{{ $loop->index + 1 }}</td>
                                                    <td class="text-start fw-bold">{{ $sale->invoice_id }}</td>
                                                    <td class="text-start">{{ date($__date_format, strtotime($sale->date)) }}</td>
                                                    <td class="text-start">{{ $sale->customer_name }}</td>
                                                    <td class="text-start fw-bold">{{ \App\Utils\Converter::format_in_bdt($sale->total_invoice_amount) }}</td>
                                                    <td class="text-start">

                                                        @if ($sale->sale_screen == \App\Enums\SaleScreenType::AddSale->value)
                                                            <a href="{{ route('sales.edit', $sale->id) }}" title="{{ __("Edit") }}"> <i class="far fa-edit text-info"></i></a>
                                                        @else
                                                            <a id="editPosSale" href="{{ route('sales.pos.edit', $sale->id, $sale->sale_screen) }}" title="{{ __("Edit") }}"> <i class="far fa-edit text-info"></i></a>
                                                        @endif

                                                        @php
                                                            $filename = $sale->invoice_id.'__'.$sale->date.'__'.$branchName;
                                                        @endphp

                                                        <a href="{{ route('sales.helper.related.voucher.print', $sale->id) }}" onclick="printSalesRelatedVoucher(this); return false;" title="{{ __("Print") }}" data-filename="{{ $filename }}"> <i class="fas fa-print text-secondary"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="fw-bold text-center" colspan="6">{{ __('Data Not Found') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();

        $('.tab_btn').removeClass('tab_active');
        $(this).addClass('tab_active');
    });

    function saleRecentTransactions(event) {

        $('#recent_trans_preloader').show();
        var url = event.getAttribute('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#recent_transection_list').html(data);
                $('#recent_trans_preloader').hide();
            },
            error: function(err) {

                $('#recent_trans_preloader').show();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }

    function printSalesRelatedVoucher(event) {

        var url = event.getAttribute('href');
        var filename = event.getAttribute('data-filename');
        var print_page_size = $('#print_page_size').val();
        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {print_page_size},
            success: function(data) {

                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 1000,
                    header: null,
                    footer: null,
                });

                document.title = filename;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    }
</script>
