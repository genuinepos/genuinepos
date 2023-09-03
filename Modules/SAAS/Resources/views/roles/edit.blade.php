<x-saas::admin-layout title="Edit Role">
    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Edit Role') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.roles.index') }}" class="btn btn-sm btn-primary">{{ __('All Users') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.roles.update', $role->id) }}" id="roleupdateForm" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="name" class="form-label"><strong>{{ __('Name') }}</strong><span class="text-danger">*</span></label>
                                <input value="{{ $role->name }}" type="text" name="name" class="form-control" id="name" placeholder="{{ __('Enter fullname') }}" required>
                            </div>

                            <div class="mt-3">
                                <input type="submit" class="btn btn-sm btn-primary" value="{{ __('Update') }}" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script></script>
    @endpush
</x-saas::admin-layout>
