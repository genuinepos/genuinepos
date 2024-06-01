<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var logTable = $('.data_tbl').DataTable({
        "processing": true,
        "serverSide": true,
        dom: "lBfrtip",
        buttons: [{
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> Pdf',
                className: 'btn btn-primary'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary'
            },
        ],
        "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        "ajax": {
            "url": "{{ route('reports.user.activities.log.index') }}",
            "data": function(d) {
                d.branch_id = $('#branch_id').val();
                d.user_id = $('#user_id').val();
                d.action = $('#action').val();
                d.subject_type = $('#subject_type').val();
                d.from_date = $('#from_date').val();
                d.to_date = $('#to_date').val();
            }
        },
        columns: [{
                data: 'date',
                name: 'date'
            },
            {
                data: 'branch',
                name: 'branches.name'
            },
            {
                data: 'action_by',
                name: 'users.name'
            },
            {
                data: 'action',
                name: 'users.last_name'
            },
            {
                data: 'subject_type',
                name: 'users.prefix'
            },
            {
                data: 'descriptions',
                name: 'descriptions'
            },
        ],
        fnDrawCallback: function() {

            $('.data_preloader').hide();
        }
    });

    //Submit filter form by select input changing
    $(document).on('submit', '#filter_form', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        logTable.ajax.reload();
    });

    $(document).on('change', '#branch_id', function(e) {

        var branch_id = $(this).val();
        getBrandAllowLoginUsers(branch_id)
    });

    function getBrandAllowLoginUsers(branchId) {

        var branchId = branchId ? branchId : 'null';

        var isOnlyAuthenticatedUser = 1;
        var allowAll = 1;
        var url = "{{ route('users.branch.users', [':isOnlyAuthenticatedUser', ':allowAll', ':branchId']) }}";
        var route = url.replace(':isOnlyAuthenticatedUser', isOnlyAuthenticatedUser);
        route = route.replace(':allowAll', allowAll);
        route = route.replace(':branchId', branchId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(data) {

                $('#user_id').empty();
                $('#user_id').append('<option value="">' + "{{ __('All') }}" + '</option>');
                $.each(data, function(key, val) {

                    var userPrefix = val.prefix != null ? val.prefix : '';
                    var userLastName = val.last_name != null ? val.last_name : '';
                    $('#user_id').append('<option value="' + val.id + '">' + userPrefix + ' ' + val.name + ' ' + userLastName + '</option>');
                });
            }
        })
    }
</script>

<script type="text/javascript">
    new Litepicker({
        singleMode: true,
        element: document.getElementById('from_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

    new Litepicker({
        singleMode: true,
        element: document.getElementById('to_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY',
    });
</script>
