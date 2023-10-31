<script>
    function activeSelectedItems() {

        $('.product-name').removeClass('ac_item');
        $('#product_list').find('tr').each(function () {

            var p_id = $(this).find('#product_id').val();
            var v_id = $(this).find('#variant_id').val() == 'noid' ? 'no_v_id' : 'vid-'+$(this).find('#variant_id').val();
            var id = p_id + v_id;

            console.log(id);
            var html = $('#' + id).html();
            console.log(html);
            $('#' + id).addClass('ac_item');
        });
    }
</script>
