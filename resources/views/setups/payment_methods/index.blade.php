@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'Payment Methods - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-glass-whiskey"></span>
                    <h5>@lang('menu.payment_method')</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> @lang('menu.back')
                </a>
            </div>

            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="section-header">
                                <div class="col-md-6">
                                    <h6>@lang('menu.all_payment_methods')</h6>
                                </div>


                                <div class="col-6 d-flex justify-content-end">
                                    <a href="{{ route('payment.methods.create') }}" class="btn btn-sm btn-primary" id="addBtn"><i class="fas fa-plus-square"></i> @lang('menu.add')</a>
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
                                                <th>{{ __("Serial") }}</th>
                                                <th>{{ __('Payment Method Name') }}</th>
                                                <th>{{ __("Action") }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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

     <!--Add/Edit payment method modal-->
     <div class="modal fade" id="paymentMethodAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
     <!--Add/Edit payment method modal End-->
@endsection
@push('scripts')
<script>
    var paymentMethodTable = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'excel',text: 'Excel', messageTop: 'Payment Methods', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'pdf',text: 'Pdf', messageTop: 'Payment Methods', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print',text: 'Print', messageTop: '<b>Payment Methods</b>', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('payment.methods.index') }}",
        "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, "All"]],
        columns: [{data: 'DT_RowIndex',name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'action',name: 'action'},
        ],
    });

    $(document).on('click', '#addBtn', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            success: function(data) {

                $('#paymentMethodAddOrEditModal .modal-dialog').remove();
                $('#paymentMethodAddOrEditModal').html(data);
                $('#paymentMethodAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#payment_method_name').focus();
                }, 500);

                $('.data_preloader').hide();

            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else {

                    toastr.error('Server Error. Please contact to the support team.');
                }
            }
        });
    });

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            cache: false,
            async: false,
            success: function(data) {

                $('#paymentMethodAddOrEditModal .modal-dialog').remove();
                $('#paymentMethodAddOrEditModal').html(data);
                $('#paymentMethodAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#payment_method_name').focus().select();
                }, 500);

                $('.data_preloader').hide();

            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else {

                    toastr.error('Server Error. Please contact to the support team.');
                }
            }
        });
    });

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-modal-primary',
                    'action': function() {
                        $('#deleted_form').submit();
                    }
                },
                'No': {
                    'class': 'no btn-danger',
                    'action': function() {
                        // alert('Deleted canceled.')
                    }
                }
            }
        });
    });

    //data delete by ajax
    $(document).on('submit', '#deleted_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url,
            type: 'post',
            async: false,
            data: request,
            success: function(data) {
                toastr.error(data);
                paymentMethodTable.ajax.reload();
                $('#deleted_form')[0].reset();
            }
        });
    });
</script>

@endpush
