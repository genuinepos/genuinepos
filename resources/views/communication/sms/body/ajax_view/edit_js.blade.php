
<script>
function bindDataToForm(data) {
    Object.values(window.editors).forEach(editor => {
        editor.setData(data.body);
    });

    // Set other form fields
    $('#add_data input[name="format"]').val(data.format);
    $('#add_data input[name="subject"]').val(data.subject);
    $('#add_data select[name="is_important"]').val(data.is_important);
    $('#edit_input').val(data.id);
}


$(document).on('click', '.edit-btn', function(){
    var id = $(this).data('id'); 
    $.ajax({
        url: "{{ route('sms-body.edit', ['sms_body' => ':body']) }}".replace(':body', id),
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
