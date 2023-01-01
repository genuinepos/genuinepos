<div class="section-header d-flex justify-content-between align-items-center px-3">
    <h6>
        <span class="fas fa-users"></span>
        @lang('menu.users')
    </h6>
    <span class="badge bg-secondary text-white">
        <div id="small-badge">
            @lang('menu.total'): {{ $userCount }}
        </div>
    </span>
</div>
<div class="widget_content">
    <div class="mtr-table">
        <div class="table-responsive">
            <table id="user_table" class="display data__table data_tble stock_table compact" width="100%">
                <thead>
                    <tr>
                        <th>@lang('menu.department')</th>
                        <th>@lang('menu.total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->department_name }}</td>
                            <td>{{ $user->total_users }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#user_table').DataTable({
        dom: "Bfrtip",
        buttons: ["excel", "pdf", "print"],
        pageLength: 10,
    });
</script>
