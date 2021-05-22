@extends('layout.master')
@push('stylesheets')
@endpush
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <!-- =====================================================================BODY CONTENT================== -->
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-desktop"></span>
                                <h5>Transfer Stocks</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> Back</a>
                        </div>
                    </div>

                    <!-- =========================================top section button=================== -->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="form_element">
                                <div class="section-header">
                                    <div class="col-md-10">
                                        <h6>All Transfer Stocks </h6>
                                    </div>
                                </div>

                                <div class="widget_content">
                                    <div class="data_preloader">
                                        <h6><i class="fas fa-spinner text-primary"></i> Processing...</h6>
                                    </div>
                                    <div class="table-responsive" id="data-list">
                                        <table class="display data_tbl data__table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Reference ID</th>
                                                    <th>Warehouse(From) </th>
                                                    <th>Branch(To)</th>
                                                    <th>Shipping Charge</th>
                                                    <th>Total Amount</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
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
    </div>

    <div id="transfer_details">
        
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('public') }}/assets/plugins/custom/print_this/printThis.js"></script>
    <script>
        var table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            aaSorting: [[3, 'asc']],
            ajax: "{{ route('transfer.stock.to.branch.index') }}",
            // columnDefs: [{
            //     "targets": [0],
            //     "orderable": false,
            //     "searchable": false
            // }],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'from'},
                {data: 'to',name: 'to'},
                {data: 'shipping_charge',name: 'shipping_charge'},
                {data: 'net_total_amount',name: 'net_total_amount'},
                {data: 'status',name: 'status'},
                {data: 'action'},
            ],
        });

        function transferDetails(url) {
            $('.data_preloader').show();
            $.get(url, function(data) {
                $('#transfer_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        }

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).closest('tr').data('href');
            transferDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:last-child)', function(e) {
            e.preventDefault();
            var url = $(this).parent().data('href');
            transferDetails(url);
        });

        // Show sweet alert for delete
        $(document).on('click', '#delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            swal({
                title: "Are you sure to delete ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) { 
                    $('#deleted_form').submit();
                } else {
                    swal("Your imaginary file is safe!");
                }
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
                    table.ajax.reload();
                    toastr.success(data, 'Succeed');
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
                loadCSS: "{{asset('public/assets/css/print/sale.print.css')}}",                      
                removeInline: false, 
                printDelay: 1000, 
                header: null,        
            });
        });
    </script>
@endpush