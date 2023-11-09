<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title">{{ __("Suspended Sales") }}</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close" tabindex="-1"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body" id="suspended_sale_list">
            <div class="suspends_area">
                <div class="row">
                    @foreach ($suspendedInvoices as $suspendedInvoice)
                        <section class="col-md-3 mt-1" id="suspended_transaction_section">
                            <div class="card bg-primary text-white pt-1">
                                <div class="card-title text-center">
                                    <h6>{{ $suspendedInvoice->suspended_id }}</h6>
                                    <h6>{{ date('d/m/Y', strtotime($suspendedInvoice->date)) }}</h6>
                                    <h6><i class="fas fa-user"></i> {{ $suspendedInvoice?->customer_name }}</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h6><i class="fas fa-cubes"></i>{{ __("Total Item") }} : {{ $suspendedInvoice->total_item }}</h6>
                                    <h6><i class="far fa-money-bill-alt"></i>{{ __("Total Amount") }}: {{ $suspendedInvoice->total_invoice_amount }}</h6>
            
                                    <div class="row mt-1">
                                        <div class="col-md-3 offset-3">
                                            <a href="{{ route('sales.pos.edit', $suspendedInvoice->id) }}" class="a btn btn-sm btn-primary" tabindex="-1">{{ __("Edit") }}</a>
                                        </div>
            
                                        <div class="col-md-3">
                                            <a id="delete" href="{{ route('sales.delete', $suspendedInvoice->id) }}" class="a btn btn-sm btn-danger" tabindex="-1">{{ __("Delete") }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
