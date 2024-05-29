<script>
    $(document).on('click', '#select_all', function() {
        var target = $(this).data('target');
        if ($(this).is(':CHECKED', true)) {

            $('.' + target).prop('checked', true);
        } else {

            $('.' + target).prop('checked', false);
        }
    });
</script>
