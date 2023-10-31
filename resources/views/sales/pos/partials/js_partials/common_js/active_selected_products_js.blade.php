<script>
    function activeSelectedItems() {

        $('.product-name').removeClass('ac_item');
        $('#product_list').find('tr').each(function() {

            var p_id = $(this).find('#product_id').val();
            var v_id = $(this).find('#variant_id').val() == 'noid' ? 'no_v_id' : 'vid-' + $(this).find('#variant_id').val();
            var id = p_id + v_id;

            var html = $('#' + id).html();
            $('#' + id).addClass('ac_item');
        });
    }

    function adjustSerial() {

        var serials = document.querySelectorAll('#serial');
        var serialsArray = Array.from(serials);

        serials.forEach(function (element, index) {

            element.innerHTML = index + 1;
        });
    }
</script>
