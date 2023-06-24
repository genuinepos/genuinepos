<script>
    function selectProductList() {
        $('.select_product_preloader').show();
        var category_id = $('#category_id').val();
        var brand_id = $('#brand_id').val();
        $.ajax({
            url: "{{ route('sales.pos.product.list') }}",
            type: 'get',
            data: {category_id,brand_id,},
            success: function(data) {
                //console.log(data);
                $('#select_product_list').html(data);
                $('.select_product_preloader').hide();
                activeSelectedItems();
            }
        });
    }
    selectProductList();

    //Submit filter form by select input changing
    $(document).on('change', '.common_submitable', function() {
        selectProductList();
    });

    $(document).on('click', '.cat-button', function(e) {
        e.preventDefault();
        var cate_id = $(this).data('id');
        $('#category_id').val(cate_id);
        selectProductList();
    });
</script>
