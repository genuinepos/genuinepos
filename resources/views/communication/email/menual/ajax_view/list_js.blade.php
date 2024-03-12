<script>
    function handleRadioChange(radio) {
        // Get the value of the selected radio button
        var selectedValue = radio.value;

        $.ajax({
            url: "{{ route('menual.edit', ['menual' => ':menualId']) }}".replace(':menualId', selectedValue),
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log(data);
                // bindDataToForm(data);
                // $('#add_data').attr('id', 'edit_data');
                // $('#add_data button[type="button"]').attr('id', 'update').text('Update');
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    }
</script>
