<table class="display data_tbl data__table table-striped">
    <thead>
        <tr>
            <th class="text-startx">@lang('menu.serial')</th>
            <th class="text-startx">@lang('menu.name')</th>
            <th class="text-startx">@lang('menu.action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($roles as $role)
            <tr>
                <td class="text-start">{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $role->name }}</td>
                <td class="text-start">
                    <div class="dropdown table-dropdown">
                        @if (auth()->user()->can('role_edit'))
                            <a href="{{ route('users.role.edit', $role->id) }}" id="edit" title="Edit details" class="action-btn c-edit" id="edit"><span class="fas fa-edit"></span>                            </a>
                        @endif

                        @if (auth()->user()->can('role_delete'))
                            <a href="{{ route('users.role.delete', $role->id) }}" class="action-btn c-delete" id="delete"><span class="fas fa-trash "></span>
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


