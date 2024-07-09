 <script>
     // this is for datatable
     var productTable = $('.data_tbl').DataTable({
         // dom: "lBfrtip",
         "processing": true,
         "serverSide": true,
         aaSorting: [
             [0, 'asc']
         ],
         "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
         "lengthMenu": [
             [10, 25, 50, 100, 500, 1000, -1],
             [10, 25, 50, 100, 500, 1000, "All"]
         ],
         "ajax": {
             "url": "{{ route('advertisements.index') }}",
         },
         columns: [{
                 data: 'content_type',
                 name: 'content_type'
             },
             {
                 data: 'title',
                 name: 'title'
             },
             {
                 data: 'attachment',
                 name: 'attachment.content_title'
             },
             {
                 data: 'status',
                 name: 'status'
             },
             {
                 data: 'action',
                 name: 'action'
             },
         ],
     });
 </script>
