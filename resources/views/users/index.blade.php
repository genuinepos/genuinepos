@extends('layout.master')
@push('stylesheets')
@endpush
@section('title', 'User List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="col-md-6">
                    <h6>{{ __('Shops') }}
                        <span style="font-size: 12px;">({{ __("User Limit") }} -<span class="text-danger">{{ $currentUserCount }}</span>/{{ $generalSettings['subscription']->features['user_count'] }})</span> | <span style="font-size: 12px;">({{ __("Employee Limit") }} -<span class="text-danger">{{ $currentUserCount }}</span>/{{ $generalSettings['subscription']->features['employee_count'] }})</span>
                    </h6>
                </div>

                <div class="col-md-6">

                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-secondary float-end back-button">
                        <i class="fas fa-long-arrow-alt-left text-white"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="p-1">
            {{-- @if ((auth()->user()->role_type == 1 || auth()->user()->role_type == 2) && auth()->user()->is_belonging_area == 0) --}}
            @if (auth()->user()->can('has_access_to_all_area') && auth()->user()->is_belonging_an_area == 0 && $generalSettings['subscription']->has_business == 1)
                <div class="row">
                    <div class="col-md-12">
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="element-body">
                                <form action="" method="get">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label><strong>@lang('menu.business_location') </strong></label>
                                            <select name="branch_id" class="form-control submit_able select2" id="branch_id">
                                                <option value="">{{ __("All") }}</option>
                                                <option value="NULL">{{ $generalSettings['business_or_shop__business_name'] }}({{ __('Business') }})</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">
                                                        @php
                                                            $parentBranchName = $branch?->parentBranch?->name;
                                                            $areaName = $branch?->area_name ? ' (' . $branch->area_name . ')' : '';
                                                            $branchCode = $branch?->branch_code ? '-(' . $branch->branch_code . ')' : '';
                                                        @endphp
                                                        {{ $branch->name . $parentBranchName . $areaName . $branchCode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Allow Login') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Shop/Business') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form id="deleted_form" action="" method="post">
                @method('DELETE')
                @csrf
            </form>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    className: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    className: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    className: '',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            // aaSorting: [[8, 'asc']],
            "pageLength": parseInt("{{ $generalSettings['system__datatables_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('users.index') }}",
                "data": function(d) {
                    d.branch_id = $('#branch_id').val();
                }
            },
            columns: [{
                    data: 'username',
                    name: 'username'
                }, {
                    data: 'allow_login',
                    name: 'username'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'phone',
                    name: 'phone'
                }

                , {
                    data: 'branch',
                    name: 'branches.name'
                }, {
                    data: 'role_name',
                    name: 'role_name'
                }, {
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'action'
                },
            ],
        });
        // table.buttons().container().appendTo('#exportButtonsContainer');
        //Submit filter form by select input changing
        $(document).on('change', '.submit_able', function() {
            table.ajax.reload();
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-modal-primary',
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
                    table.ajax.reload();
                    toastr.error(data);
                }, error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        });
    </script>
@endpush
