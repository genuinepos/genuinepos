<script>

function bindDataToForm(data) {
    $('#add_data input[name="server_name"]').val(data.server_name);
    $('#add_data input[name="host"]').val(data.host);
    $('#add_data input[name="port"]').val(data.port);
    $('#add_data input[name="user_name"]').val(data.user_name);
    $('#add_data input[name="password"]').val(data.password);
    $('#add_data input[name="encryption"]').val(data.encryption);
    $('#add_data input[name="address"]').val(data.address);
    $('#add_data input[name="name"]').val(data.name);
    $('#add_data select[name="status"]').val(data.status);
    $('#edit_input').val(data.id);
}

$(document).on('click', '.edit-btn', function(){
    var serverId = $(this).data('id'); 
    $.ajax({
        url: "{{ route('servers.edit', ['server' => ':server']) }}".replace(':server', serverId),
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
