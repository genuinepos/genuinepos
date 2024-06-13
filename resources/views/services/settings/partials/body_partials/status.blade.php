<div class="tab_contant status d-hide" id="tab_contant">
    <div class="section-header">
        <div class="col-md-6">
            <h6>{{ __('List of Status') }}</h6>
        </div>

        <div class="col-6 d-flex justify-content-end">
            @if (auth()->user()->can('status_create'))
                <a href="{{ route('services.settings.status.create') }}" class="btn btn-sm btn-primary" id="addStatus"><i class="fas fa-plus-square"></i> {{ __('Add Status') }}</a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table_area">
                <div class="table-responsive">
                    <table id="status-table" class="display data_tbl data__table common-reloader w-100">
                        <thead>
                            <tr>
                                {{-- <th>{{ __('S/L') }}</th> --}}
                                <th>{{ __('Status Name') }}</th>
                                <th>{{ __('Color') }}</th>
                                <th>{{ __('Sort Order') }}</th>
                                <th>{{ __('Created By') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (auth()->user()->can('status_delete'))
    <form id="delete_status_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endif
