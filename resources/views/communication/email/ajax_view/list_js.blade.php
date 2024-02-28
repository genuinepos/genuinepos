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
                "url": "{{ route('servers.index') }}",
            },
            columns: [
                { data: 'server_name', name: 'server_name' },
                { data: 'host', name: 'host' },
                { data: 'port', name: 'port' },
                { data: 'user_name', name: 'user_name' },
                { data: 'password', name: 'password' },
                { data: 'encryption', name: 'encryption' },
                { data: 'address', name: 'address' },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' },
            ],
        });
</script>