<script>
    $('.leave_types').hide();
    $(document).on('click', '#tab_btn', function() {

        $('#addLeave').hide();
        $('#addLeaveType').hide();
        var showing = $(this).data('show');

        if (showing == 'leaves') {

            $('#addLeave').show();
        } else {

            $('#addLeaveType').show();
        }
    });

    $(document).on('click', '#tab_btn', function(e) {
        e.preventDefault();
        $('.tab_btn').removeClass('tab_active');
        $('.tab_contant').hide();
        var show_content = $(this).data('show');
        $('.' + show_content).show();
        $(this).addClass('tab_active');
    });
</script>
