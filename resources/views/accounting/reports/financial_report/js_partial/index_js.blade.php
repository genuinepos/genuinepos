<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getFinancialReport() {

        $('.data_preloader').show();
        var branch_id = $('#branch_id').val();
        var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        // console.log(branch_id);
        $.ajax({
            url: "{{ route('reports.financial.amounts') }}",
            type: 'GET',
            data: {
                branch_id,
                child_branch_id,
                from_date,
                to_date
            },
            success: function(data) {

                $('#data-list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getFinancialReport();

    // //Print purchase Payment report
    $(document).on('submit', '#filter_financial_report', function(e) {
        e.preventDefault();
        getFinancialReport();
    });

    //Print financial report
    $(document).on('click', '#printReport', function(e) {
        e.preventDefault();
        var url = "{{ route('reports.financial.report.print') }}";

        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var child_branch_id = $('#child_branch_id').val();
        var child_branch_name = $('#child_branch_id').find('option:selected').data('child_branch_name');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var currentTitle = document.title;

        $.ajax({
            url: url,
            type: 'get',
            data: {
                branch_id,
                branch_name,
                child_branch_id,
                child_branch_name,
                from_date,
                to_date
            },
            success: function(data) {

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{ asset('assets/css/print/sale.print.css') }}",
                    removeInline: false,
                    printDelay: 700,
                    header: null,
                });

                var tempElement = document.createElement('div');
                tempElement.innerHTML = data;
                var filename = tempElement.querySelector('#title');

                document.title = filename.innerHTML;

                setTimeout(function() {
                    document.title = currentTitle;
                }, 2000);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).on('change', '#branch_id', function(e) {
        e.preventDefault();

        var branchId = $(this).val();

        $('#child_branch_id').empty();
        $('#child_branch_id').append('<option data-child_branch_name="" value="">' + "{{ __('All') }}" + '</option>');

        if (branchId == '') {
            return;
        }

        var route = '';
        var url = "{{ route('branches.parent.with.child.branches', ':branchId') }}";
        route = url.replace(':branchId', branchId);

        $.ajax({
            url: route,
            type: 'get',
            success: function(branch) {

                if (branch.child_branches.length > 0) {

                    $('#child_branch_id').empty();
                    $('#child_branch_id').append('<option data-child_branch_name="' + "{{ __('All') }}" + '" value="">' + "{{ __('All') }}" + '</option>');
                    $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + branch.area_name + ')' + '" value="' + branch.id + '">' + branch.name + '(' + branch.area_name + ')' + '</option>');

                    $.each(branch.child_branches, function(key, childBranch) {

                        $('#child_branch_id').append('<option data-child_branch_name="' + branch.name + '(' + childBranch.area_name + ')' + '" value="' + childBranch.id + '">' + branch.name + '(' + childBranch.area_name + ')' + '</option>');
                    });
                }
            },
            error: function(err) {

                if (err.status == 0) {

                    toastr.error("{{ __('Net Connetion Error.') }}");
                    return;
                } else if (err.status == 500) {

                    toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                    return;
                }
            }
        });
    });

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
