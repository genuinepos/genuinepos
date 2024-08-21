<script>
    // Add Pos Shortcut Menu Script
    $(document).on('click', '#addPosShortcutBtn', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {
            $('#shortcutMenuModal').html(data);
            $('#shortcutMenuModal').modal('show');
        });
    });

    $(document).on('change', '#check_menu', function() {
        $('#add_shortcut_menu_form').submit();
    });

    $(document).on('submit', '#add_shortcut_menu_form', function(e) {
        e.preventDefault();

        var url = $(this).attr('action');
        var request = $(this).serialize();

        $.ajax({
            url: url,
            type: 'post',
            data: request,
            success: function(data) {

                toastr.success(data);
                allPosShortcutMenus();
            }
        });
    });

    // Get all pos shortcut menus by ajax
    function allPosShortcutMenus() {
        $.ajax({
            url: "{{ route('short.menus.show', \App\Enums\ShortMenuScreenType::PosScreen->value) }}",
            type: 'get',
            success: function(data) {

                $('#pos-shortcut-menus').html(data);
            }
        });
    }
    allPosShortcutMenus();
</script>
