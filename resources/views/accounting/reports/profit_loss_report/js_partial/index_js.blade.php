<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function getSalePurchaseAndProfitData() {
        $('.data_preloader').show();

        var branch_id = $('#branch_id').val();
        var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var filterDate = {
            branch_id,
            child_branch_id,
            from_date,
            to_date,
        }

        $.ajax({
            url: "{{ route('reports.profit.loss.amounts') }}",
            type: 'get',
            data: filterDate,
            success: function(data) {
                $('#data_list').html(data);
                $('.data_preloader').hide();
            }
        });
    }
    getSalePurchaseAndProfitData();

    //Send sale purchase profit filter request
    $('#profit_loss_filter_form').on('submit', function(e) {
        e.preventDefault();
        getSalePurchaseAndProfitData();
    });

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

    //Print Profit/Loss
    $(document).on('click', '#printReport', function(e) {
        e.preventDefault();

        var url = "{{ route('reports.profit.loss.print') }}";
        var branch_id = $('#branch_id').val();
        var branch_name = $('#branch_id').find('option:selected').data('branch_name');
        var child_branch_id = $('#child_branch_id').val() ? $('#child_branch_id').val() : null;
        var child_branch_name = $('#child_branch_id').find('option:selected').data('child_branch_name');
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        var filterDate = {
            branch_id,
            branch_name,
            child_branch_id,
            child_branch_name,
            from_date,
            to_date,
        }

        $.ajax({
            url: url,
            type: 'get',
            data: filterDate,
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
