<script>

function bindDataToForm(data) {
    $('#add_data input[name="server_name"]').val(data.server_name);
    $('#add_data input[name="host"]').val(data.host);
    $('#add_data input[name="api_key"]').val(data.api_key);
    $('#add_data input[name="sender_id"]').val(data.sender_id);
    $('#add_data select[name="status"]').val(data.status);
    $('#edit_input').val(data.id);
}

$(document).on('click', '.edit-btn', function(){
    var serverId = $(this).data('id'); 
    $.ajax({
        url: "{{ route('sms-server.edit', ['sms_server' => ':server']) }}".replace(':server', serverId),
        type: "GET",
        dataType: "json",
        success: function(data){
          bindDataToForm(data);
          $('#add_data').attr('id', 'edit_data');
          $('#add_data button[type="button"]').attr('id', 'update').text('Update'); 
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
});
</script>
