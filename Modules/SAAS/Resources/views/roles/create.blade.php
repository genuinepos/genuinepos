<x-saas::admin-layout title="Create Role">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Create Role') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.roles.index') }}" class="btn btn-sm btn-primary">{{ __('All Users') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.roles.store') }}" id="roleStoreForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="name" class="form-label"><strong>{{ __('Role Name') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Enter role name') }}" required>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <div class="form-check my-3">
                                    <input type="checkbox" name="permissions[select_all]" class="permissions form-check-input" id="select-all">
                                    <label class="form-check-label" for="select-all"><b>{{ __('Permissions') }}</b></label>
                                </div>
                                @foreach ($permissions as $permission)
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="permissions[{{ $permission->name }}]" class="permissions select-child form-check-input" id="{{ $permission->id }}">
                                        <label class="form-check-label" for="{{ $permission->id }}">{{ str($permission->name)->headline() }}</label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-sm btn-primary mt-3" value="">{{ __('Create Role') }}</button>
                                <a href="{{ route('saas.roles.index') }}" class="btn btn-sm btn-secondary mt-3">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.querySelector('#select-all').addEventListener('click', function(e) {
                var isChecked = this.checked;
                document.querySelectorAll('.select-child').forEach((el) => {
                    if (isChecked) {
                        el.checked = true;
                    } else {
                        el.checked = false;
                    }
                })
            });
        </script>
    @endpush
</x-saas::admin-layout>
