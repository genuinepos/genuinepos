@extends('layout.master')
@push('stylesheets')@endpush
@section('title', 'All Transfer(Warehouse To Business Location) - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-exchange-alt"></span>
                                 <h5>@lang('menu.transfer_stocks')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>{{ __("All Transfer Stocks") }} </h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list"  style="min-height: 50vh;">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th>@lang('menu.date')</th>
                                                <th>@lang('menu.reference_id')</th>
                                                <th>@lang('menu.warehouse')(From) </th>
                                                <th>@lang('menu.b_location')(To)</th>
                                                <th>@lang('menu.shipping_charge')({{ $generalSettings['business__currency'] }})</th>
                                                <th>@lang('menu.total_amount') ({{ $generalSettings['business__currency'] }})</th>
                                                <th>@lang('menu.status')</th>
                                                <th>@lang('menu.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="transfer_details"></div>
@endsection
@push('scripts')
    <script>
        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'excel',text: 'Excel',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: 'Pdf',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: 'Print',className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            ajax: "{{ route('transfer.stock.to.branch.index') }}",
            columnDefs: [{"targets": [4, 7],"orderable": false,"searchable": false}],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'warehouses.warehouse_name'},
                {data: 'to',name: 'branches.name'},
                {data: 'shipping_charge',name: 'shipping_charge', className: 'text-end'},
                {data: 'net_total_amount',name: 'net_total_amount', className: 'text-end'},
                {data: 'status',name: 'status'},
                {data: 'action'},
            ],
        });

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#transfer_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Confirmation',
                'message': 'You are about to delete this item. <br/>It cannot be restored at a later time! Continue?',
                'buttons': {'Yes': {'btnClass': 'yes btn-danger','action': function() {$('#deleted_form').submit();}},'No': {'class': 'no btn-modal-primary','action': function() {console.log('Deleted canceled.');}}}
            });
        });

        //data delete by ajax
        $(document).on('submit', '#deleted_form',function(e){
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data:request,
                success:function(data){

                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg, 'Attention');
                        return;
                    }
                    table.ajax.reload();
                    toastr.error(data);
                }
            });
        });

        // Make print
        $(document).on('click', '.print_btn',function (e) {
           e.preventDefault();
            var body = $('.transfer_print_template').html();
            var header = $('.heading_area').html();
            $(body).printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                loadCSS: "{{asset('assets/css/print/sale.print.css')}}",
                removeInline: false,
                printDelay: 1000,
                header: null,
            });
        });
    </script>
@endpush