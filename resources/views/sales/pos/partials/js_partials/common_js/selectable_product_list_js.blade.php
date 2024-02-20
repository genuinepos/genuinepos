<script>
    function selectableProductList() {
        // $('.select_product_preloader').show();
        var category_id = $('#pos_category_id').val();
        var brand_id = $('#pos_brand_id').val();

        $.ajax({
            url: "{{ route('sales.helper.pos.selectable.products') }}",
            type: 'get',
            data: { category_id, brand_id },
            success: function(data) {

                $('#select_product_list').html(data);
                $('.select_product_preloader').hide();
                activeSelectedItems();
            }
        });
    }
    selectableProductList();

    //Submit filter form by select input changing
    $(document).on('change', '#pos_category_id', function() {
        selectableProductList();
    });

    $(document).on('change', '#pos_brand_id', function() {
        selectableProductList();
    });

    $(document).on('click', '.cat-button', function(e) {
        e.preventDefault();
        var cate_id = $(this).data('id');
        $('#pos_category_id').val(cate_id);
        selectableProductList();
    });
</script>
