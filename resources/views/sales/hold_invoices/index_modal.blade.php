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
            <h6 class="modal-title" id="exampleModalLabel">{{ __('Hold Invoices') }}</h6>
            <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
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
                                            <th class="text-start">{{ __('Hold Invoice ID') }}</th>
                                            <th class="text-start">{{ __('Date') }}</th>
                                            <th class="text-start">{{ __('Customer') }}</th>
                                            <th class="text-start">{{ __('Total Amount') }}</th>
                                            <th class="text-start">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="data-list" id="recent_transection_list">
                                        @if (count($holdInvoices))
                                            @foreach ($holdInvoices as $holdInvoice)
                                                <tr>
                                                    <td class="text-start fw-bold">{{ $loop->index + 1 }}</td>
                                                    <td class="text-start fw-bold">{{ $holdInvoice->hold_invoice_id }}</td>
                                                    <td class="text-start">{{ date($__date_format, strtotime($holdInvoice->date)) }}</td>
                                                    <td class="text-start">{{ $holdInvoice->customer_name }}</td>
                                                    <td class="text-start fw-bold">{{ \App\Utils\Converter::format_in_bdt($holdInvoice->total_invoice_amount) }}</td>
                                                    <td class="text-start">
                                                        <a id="editPosSale" href="{{ route('sales.pos.edit', $holdInvoice->id) }}" title="Edit" class=""> <i class="far fa-edit text-info"></i></a>
                                                        <a id="delete" href="{{ route('sales.delete', $holdInvoice->id) }}" tabindex="-1"><i class="fas fa-trash-alt text-danger"></i></a>
                                                        <a href="{{ route('sales.print', $holdInvoice->id) }}" onclick="printSale(this); return false;" title="Print" class=""> <i class="fas fa-print text-secondary"></i></a>
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
    function printSale(event) {

        var url = event.getAttribute('href');

        $.ajax({
            url: url,
            type: 'get',
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
                    printDelay: 500,
                    header: null,
                    footer: null,
                });
            },
            error: function(err) {

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
