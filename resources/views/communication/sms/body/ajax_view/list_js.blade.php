 <script>
      var productTable = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            aaSorting: [[0, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sms-body.index') }}",
            },
            columns: [
                { data: 'is_important', name: 'is_important' },
                { data: 'format', name: 'format' },
                { data: 'action', name: 'action' },
            ],
        });
</script>