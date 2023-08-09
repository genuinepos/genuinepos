<script>
    // Add Pos Shortcut Menu Script
    $(document).on('click', '#addPosShortcutBtn', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function (data) {
            $('#modal-body_shortcuts').html(data);
            $('#shortcutMenuModal').modal('show');
        });
    });

    $(document).on('change', '#check_menu', function () {
        $('#add_pos_shortcut_menu').submit();
    });

    $(document).on('submit', '#add_pos_shortcut_menu', function (e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function (data) {

                allPosShortcutMenus();
            }
        });
    });
</script>
