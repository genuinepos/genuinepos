<script>
    $(document).ready(function() {
        $(document).on('change', '#super_select_all', function() {

            var checkboxes = document.querySelectorAll('.accordion input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {

                checkbox.checked = !checkbox.checked;
            });
        });
    });
</script>
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
