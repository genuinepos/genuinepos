 <script>
      var productTable = $('.data_tbl').DataTable({
            // dom: "lBfrtip",
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sms-server.index') }}",
            },
            columns: [
                { data: 'server_name', name: 'server_name' },
                { data: 'host', name: 'host' },
                { data: 'api_key', name: 'api_key' },
                { data: 'sender_id', name: 'sender_id' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ],
        });
</script>