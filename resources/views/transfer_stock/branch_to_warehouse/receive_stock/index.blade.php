@extends('layout.master')
@push('stylesheets') @endpush
@section('title', 'Receive Stocks(From Business Location) - ')
@section('content')
    <div class="body-woaper">
        <div class="container-fluid">
            <div class="row">
                <div class="border-class">
                    <div class="main__content">
                        <div class="sec-name">
                            <div class="name-head">
                                <span class="fas fa-check-double"></span>
                                <h5>@lang('menu.receive_stocks')</h5>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i
                                    class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')</a>
                        </div>
                    </div>

                    <div class="p-3">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-10">
                                    <h6>@lang('menu.all_transferred_stocks') <small>(@lang('menu.from_business_location'))</small></h6>
                                </div>
                            </div>

                            <div class="widget_content">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')...</h6>
                                </div>
                                <div class="table-responsive" id="data-list">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.date')</th>
                                                <th class="text-start">@lang('menu.reference_id')</th>
                                                <th class="text-start">@lang('menu.warehouse')(From)</th>
                                                <th class="text-start">@lang('menu.b_location')(To)</th>
                                                <th class="text-start">@lang('menu.total_item')</th>
                                                <th class="text-start">@lang('menu.total_qty')</th>
                                                <th class="text-start">@lang('menu.status')</th>
                                                <th class="text-center">@lang('menu.action')</th>
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

    <div id="transfer_details">

    </div>

     <!-- Send mail modal-->
     <div class="modal fade" id="sendMailModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="product_info">@lang('menu.send_mail')</h6>
                    <a href="" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="send_mail_form" action="" method="POST">
                        @csrf
                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.to') </strong> </label>
                            <select required name="user_email" class="form-control" id="user_email">
                                <option value="">@lang('menu.select_user')</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->email }}">{{ $user->prefix.' '.$user->name.' '.$user->last_name.' ('.$user->email.')' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label><strong>@lang('menu.mail_note')</strong> </label>
                            <textarea name="mail_note" class="form-control" cols="30" rows="4"></textarea>
                        </div>

                        <div class="form-group text-end mt-3">
                            <button type="submit" class="btn btn-sm btn-success">@lang('menu.send')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Send mail modal End-->

@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/custom/print_this/printThis.js') }}"></script>
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
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
            ajax: "{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}",
            columnDefs: [{"targets": [2, 3, 4, 7],"orderable": false,"searchable": false}],
            columns: [
                {data: 'date', name: 'date'},
                {data: 'invoice_id',name: 'invoice_id'},
                {data: 'from',name: 'from'},
                {data: 'to',name: 'to'},
                {data: 'total_item',name: 'total_item'},
                {data: 'total_send_qty',name: 'total_send_qty'},
                {data: 'status',name: 'status'},
                {data: 'action'},
            ],
        });

        function transferDetails(url) {
            $('.data_preloader').show();
            console.log("URL", url);
            $.get(url, function(data) {
                console.log(data);
                $('#transfer_details').html(data);
                $('.data_preloader').hide();
                $('#detailsModal').modal('show');
            });
        }

        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            // console.log("Getting", $(this).attr('href'))
            // var url = $(this).closest('tr').data('href');
            var url = $(this).attr('href');
            transferDetails(url);
        });

        // Show details modal with data by clicking the row
        $(document).on('click', 'tr.clickable_row td:not(:last-child)', function(e) {
            e.preventDefault();
            var url = $(this).parent().data('href');
            transferDetails(url);
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

        // Show send mail modal
        $(document).on('click', '#send_mail', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#send_mail_form').attr('action', url);
            $('#sendMailModal').modal('show');
        });

        // Submit send mail form to send mail
        $(document).on('submit', '#send_mail_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('#send_mail_form')[0].reset();
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
