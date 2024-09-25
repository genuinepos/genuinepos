<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/plugins/custom/select_li/selectli.js') }}"></script>
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: 'Pdf',
                className: 'btn btn-primary'
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-primary'
            },
        ],
        "processing": true,
        "serverSide": true,
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('purchases.products.index') }}",
            "data": function(d) {
                d.product_id = $('#product_id').val();
                d.variant_id = $('#variant_id').val();
                d.branch_id = $('#branch_id').val();
                d.supplier_account_id = $('#supplier_account_id').val();
                d.category_id = $('#category_id').val();
                d.sub_category_id = $('#sub_category_id').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'purchases.date'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'product',
                name: 'products.name',
                className: 'fw-bold'
            },
            {
                data: 'supplier_name',
                name: 'suppliers.name as supplier_name'
            },
            {
                data: 'invoice_id',
                name: 'purchases.invoice_id'
            },
            {
                data: 'lot_no',
                name: 'purchase_products.lot_no'
            },
            {
                data: 'quantity',
                name: 'products.product_code',
                className: 'text-end fw-bold'
            },
            {
                data: 'net_unit_cost',
                name: 'product_variants.variant_code',
                className: 'text-end fw-bold'
            },
            {
                data: 'subtotal',
                name: 'subtotal',
                className: 'text-end fw-bold'
            },
        ],
        fnDrawCallback: function() {
            var total_qty = sum_table_col($('.data_tbl'), 'qty');
            $('#total_qty').text(bdFormat(total_qty));
            var total_subtotal = sum_table_col($('.data_tbl'), 'subtotal');
            $('#total_subtotal').text(bdFormat(total_subtotal));
            $('.data_preloader').hide();
        }
    });

    function sum_table_col(table, class_name) {
        var sum = 0;
        table.find('tbody').find('tr').each(function() {
            if (parseFloat($(this).find('.' + class_name).data('value'))) {
                sum += parseFloat(
                    $(this).find('.' + class_name).data('value')
                );
            }
        });
        return sum;
    }

    // Show details modal with data
    $(document).on('click', '#details_btn', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {

                $('#details').html(data);
                $('#detailsModal').modal('show');
                $('.data_preloader').hide();
            },
            error: function(err) {

                $('.data_preloader').hide();
                if (err.status == 0) {

                    toastr.error("{{ __('Net Connection Error.') }}");
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                }
            }
        });
    });

    $('#category_id').on('change', function() {
        var categoryId = $(this).val();

        if (categoryId == '') {

            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">' + "{{ __('Select Category First') }}" + '</option>');
        }

        var url = "{{ route('subcategories.by.category.id', ':categoryId') }}";
        var route = url.replace(':categoryId', categoryId);

        $.get(route, function(subCategories) {

            $('#sub_category_id').empty();
            $('#sub_category_id').append('<option value="">' + "{{ __('All') }}" + '</option>');
            $.each(subCategories, function(key, val) {

                $('#sub_category_id').append('<option value="' + val.id + '">' + val.name + '</option>');
            });
        });
    });

    //Submit filter form by select input changing
    $(document).on('click', '#filter_button', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        table.ajax.reload();
    });
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('from_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('to_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    $('#search_product').on('input', function() {

        $('.search_result').hide();

        $('#list').empty();
        var keyword = $(this).val();

        console.log(keyword);

        if (keyword === '') {

            $('.search_result').hide();
            $('#product_id').val('');
            $('#variant_id').val('');
            return;
        }

        var branchId = "{{ $ownBranchIdOrParentBranchId }}";

        var url = "{{ route('general.product.search.by.only.name', ':keyword', ':branchId') }}";
        var route = url.replace(':keyword', keyword);
        route = route.replace(':branchId', branchId);

        $.ajax({
            url: route,
            async: true,
            type: 'get',
            success: function(data) {

                if (!$.isEmptyObject(data.noResult)) {

                    $('.search_result').hide();
                } else {

                    $('.search_result').show();
                    $('#list').html(data);
                }
            }
        });
    });

    $(document).on('click', '#select_product', function(e) {
        e.preventDefault();

        var product_name = $(this).html();
        $('#search_product').val(product_name.trim());
        var product_id = $(this).data('p_id');
        var variant_id = $(this).data('v_id');
        $('#product_id').val(product_id);
        $('#variant_id').val(variant_id);
        $('.search_result').hide();
    });

    //Submit filter form by date-range field blur
    $(document).on('click', '#search_product', function() {
        $(this).val('');
        $('#product_id').val('');
        $('#variant_id').val('');
    });

    $('body').keyup(function(e) {

        if (e.keyCode == 13 || e.keyCode == 9) {

            $(".selectProduct").click();
            $('.search_result').hide();
            $('#list').empty();
        }
    });

    $(document).on('mouseenter', '#list>li>a', function() {
        $('#list>li>a').removeClass('selectProduct');
        $(this).addClass('selectProduct');
    });

    $(document).on('click', function(e) {

        if ($(e.target).closest(".search_result").length === 0) {

            $('.search_result').hide();
            $('#list').empty();
        }
    });
</script>
