 <script>
     // this is for datatable
     var advertisementsTable = $('.data_tbl').DataTable({
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
                 data: 'branch',
                 name: 'branch.name'
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

     $(document).on('click', '#delete', function(e) {
         e.preventDefault();
         var url = $(this).attr('href');
         $('#deleted_form').attr('action', url);
         $.confirm({
             'title': 'Confirmation',
             'content': 'Are you sure?',
             'buttons': {
                 'Yes': {
                     'class': 'yes btn-modal-primary',
                     'action': function() {
                         $('#deleted_form').submit();
                     }
                 },
                 'No': {
                     'class': 'no btn-danger',
                     'action': function() {
                         console.log('Deleted canceled.');
                     }
                 }
             }
         });
     });

     //data delete by ajax
     $(document).on('submit', '#deleted_form', function(e) {
         e.preventDefault();
         var url = $(this).attr('action');
         var request = $(this).serialize();
         $.ajax({
             url: url,
             type: 'post',
             data: request,
             success: function(data) {

                 if (!$.isEmptyObject(data.errorMsg)) {

                     toastr.error(data.errorMsg);
                     return;
                 }

                 toastr.error(data);
                 advertisementsTable.ajax.reload(null, false);
             },
             error: function(err) {

                 if (err.status == 0) {

                     toastr.error("{{ __('Net Connetion Error.') }}");
                     return;
                 } else if (err.status == 500) {

                     toastr.error("{{ __('Server Error. Please contact to the support team.') }}");
                     return;
                 }

                 toastr.error(err.responseJSON.message);
             }
         });
     });
 </script>
