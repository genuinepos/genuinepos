<script>
    function getData(status) {
        if (status == 4) {
            $('#permanent_delete').show();
            $('#bulkForceDeleteButton').hide();
        } else {
            $('#permanent_delete').hide();
            $('#bulkForceDeleteButton').show();
        }
        $("#status_id").val(status);
        $.ajax({
            url: "{{ route('send.index') }}",
            type: 'GET',
            data: {
                status: status,
            },
            success: function(response) {

                var table = '<table class="table">';
                table += '<thead class="table table-bordered"><tr><th>Check</th> <th>Email</th> <th>Group</th> <th>Attachment</th> <th>Subject</th> <th>Time</th> <th>Action</th> </tr></thead>';
                table += '<tbody>';
                $.each(response.data, function(index, item) {
                    var actionBtn = '';
                    if (item.deleted_at !== null) {
                        actionBtn = '<a href="#" class="restore-btn btn btn-success btn-sm text-white ms-2" title="Restore" data-id="' + item.id + '"><span class="fas fa-undo"></span></a>';
                    } else {
                        actionBtn = '<a href="#" class="delete-btn btn btn-danger btn-sm text-white ms-2" title="Delete" data-id="' + item.id + '"><span class="fas fa-trash"></span></a>';
                    }
                    table += '<tr>';
                    table += '<td><input type="checkbox" class="checkbox-item" data-id="' + item.id + '"></td>';
                    table += '<td>' + item.mail + '</td>';
                    table += '<td>' + item.group_name + '</td>';
                    table += '<td>' + item.attachment + '</td>';
                    table += '<td>' + item.subject + '</td>';
                    table += '<td>' + formatTime(item.created_at) + '</td>';
                    table += '<td>';
                    table += actionBtn;
                    table += '</td>';
                    table += '</tr>';
                });
                table += '</tbody></table>';
                var tabContentId = '#v-pills-' + getStatusTabName(status);
                $(tabContentId).html(table);
                initializeDataTable(tabContentId + ' table');
            },
            error: function(xhr, status, error) {
                var errorMessage = "Error fetching data.";
                toastr.error(errorMessage);
            }
        });
    }

    function initializeDataTable(tableId) {
        $(tableId).DataTable();
    }

    function getStatusTabName(status) {
        switch (status) {
            case 0:
                return 'home';
            case 1:
                return 'profile';
            case 2:
                return 'messages';
            case 3:
                return 'settings';
            case 4:
                return 'settingss';
            default:
                return '';
        }
    }

    function formatTime(timestamp) {
        var currentDate = new Date();
        var diffMs = currentDate - new Date(timestamp);
        var diffDays = Math.floor(diffMs / 86400000);
        var diffHrs = Math.floor((diffMs % 86400000) / 3600000);
        var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);

        if (diffDays > 0) {
            return diffDays + " days ago";
        } else if (diffHrs > 0) {
            return diffHrs + " hours ago";
        } else {
            return diffMins + " minutes ago";
        }
    }


    $('.checkbox-toggle').click(function() {
        var allChecked = true;
        $('.checkbox-item').each(function() {
            if (!$(this).prop('checked')) {
                allChecked = false;
                return false;
            }
        });

        if (allChecked) {
            $('.checkbox-item').prop('checked', false);
            $('.checkbox-toggle').prop('checked', false);
        } else {
            $('.checkbox-item').prop('checked', true);
            $('.checkbox-toggle').prop('checked', true);
        }
    });
</script>