<script>
    function activeSelectedItems() {

        $('.product-name').removeClass('ac_item');
        $('#product_list').find('tr').each(function () {
            console.log('selected');
            var p_id = $(this).find('#product_id').val();
            var v_id = $(this).find('#variant_id').val();
            var id = p_id + v_id;
            $('#' + id).addClass('ac_item');
        });
    }
</script>
