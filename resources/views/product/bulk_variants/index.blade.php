@extends('layout.master')
@push('stylesheets')

@endpush
@section('title', 'Variants - ')
@section('content')
    <div class="body-woaper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <span class="fas fa-cubes"></span>
                    <h5>{{ __("Variants") }}</h5>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button"><i class="fas fa-long-arrow-alt-left text-white"></i> {{ __("Back") }}</a>
            </div>
        </div>

        <div class="p-1">
            <div class="row g-lg-3 g-1">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>{{ __("List Of Varients") }}</h6>
                            </div>

                            <div class="col-6 d-flex justify-content-end">
                                @if (auth()->user()->can('variant'))
                                    <a href="{{ route('product.bulk.variants.create') }}" class="btn btn-sm btn-primary" id="addVariant"><i class="fas fa-plus-square"></i> {{ __("Add Variant") }}</a>
                                @endif
                            </div>
                        </div>

                        <div class="widget_content">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> {{ __("Processing") }}...</h6>
                            </div>
                            <div class="table-responsive" id="data-list">
                                <table class="display data_tbl data__table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">{{ __("Variant Name") }}</th>
                                            <th class="text-start">{{ __("Variant Child") }}</th>
                                            <th class="text-start">{{ __("Created By") }}</th>
                                            <th class="text-start">{{ __("Action") }}</th>
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

    <div class="modal fade" id="variantAddOrEditModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>
    // Get all Bulk Variants by ajax
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf', 'title' : 'List of Bulk Variants', text: 'Pdf', className: 'btn btn-primary',exportOptions: {columns: 'th:not(:last-child)'}},
            {extend: 'print', 'title' : 'List of Bulk Variants', className: 'btn btn-primary', autoPrint: true, exportOptions: {columns: ':visible'}}
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('product.bulk.variants.index') }}",
        columns: [
            {data: 'name',name: 'name'},
            {data: 'bulk_variant_child', name: 'name'},
            {data: 'created_by',name: 'name'},
            {data: 'action',name: 'action'},
        ],
    });

    // Setup ajax for csrf token.
    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).on('click', '#addVariant', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#variantAddOrEditModal').html(data);
                $('#variantAddOrEditModal').modal('show');

                setTimeout(function() {

                    $('#variant_name').focus();
                }, 500);
            }, error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    // pass editable data to edit modal fields
    $(document).on('click', '#edit', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('.data_preloader').show();
        $.ajax({
            url: url
            , type: 'get'
            , success: function(data) {

                $('#variantAddOrEditModal').empty();
                $('#variantAddOrEditModal').html(data);
                $('#variantAddOrEditModal').modal('show');
                $('.data_preloader').hide();
                setTimeout(function() {

                    $('#variant_name').focus().select();
                }, 500);
            }
            , error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error. Reload This Page.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

    $(document).on('click', '#delete',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Confirmation',
            'content': 'Are you sure, you want to delete?',
            'buttons': {
                'Yes': {'class': 'yes btn-modal-primary','action': function() {$('#deleted_form').submit();}},
                'No': {'class': 'no btn-danger','action': function() {console.log('Deleted canceled.')}
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
            data: request,
            success: function(data) {
                toastr.error(data);
                table.ajax.reload();
            }
        });
    });
</script>
@endpush
