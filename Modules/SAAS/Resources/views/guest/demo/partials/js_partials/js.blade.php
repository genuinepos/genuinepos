<script>
    // Domain Check
    var typingTimer; //timer identifier
    var doneTypingInterval = 800; //time in ms, 5 seconds for example
    var $input = $('#domain');

    //on keyup, start the countdown
    $input.on('keyup', function() {

        if ($input.val() == '') {

            $('#domainPreview').html('');
            return;
        }

        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown
    $input.on('keydown', function() {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping() {
        $('#domainPreview').html(`<span class="">🔍Checking availability...<span>`);
        var domain = $('#domain').val();

        if ($input.val() == '') {

            $('#domainPreview').html('');
            return;
        }

        $.ajax({
            url: "{{ route('saas.domain.checkAvailability') }}",
            type: 'GET',
            data: {
                domain: domain
            },
            success: function(res) {

                if ($input.val() == '') {

                    $('#domainPreview').html('');
                    return;
                }

                if (res.isAvailable) {
                    isAvailable = true;
                    $('#domainPreview').html(`<span class="text-success">✔ Doamin is available<span>`);
                }
            },
            error: function(err) {
                isAvailable = false;
                $('#domainPreview').html(`<span class="text-danger">❌ Doamin is not available<span>`);
            }
        });
    }
</script>
