{{-- This feature is dangerous to have, so skipped for now. --}}

<x-saas::admin-layout title="Delete Customer">
    @push('css')
        <style>
             .details_table th{
                font-size: 11px!important;
                font-weight: 600;
            }

            .details_table td {
                font-size: 11px !important;
            }
        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Delete Customer') }}</h5>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-sm details_table">
                        <tr>
                            <th>{{ __('Customer Name') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Phone') }}</th>
                            <td class="text-start">: {{ $tenant?->user?->phone }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table table-sm details_table">
                        <tr>
                            <th>{{ __('Company Name') }}</th>
                            <td class="text-start">: {{ $tenant->name }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('Subdomain') }}</th>
                            <td class="text-start">: {{ $tenant?->id }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('App Url') }}</th>
                            <td class="text-start">: {{ \Modules\SAAS\Utils\UrlGenerator::generateFullUrlFromDomain($tenant->id) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <form id="delete_form" action="{{ route('saas.tenants.destroy', $tenant->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="">
                            <label for="password" class="form-label text-bold"><b>{{ __('Enter Your Password') }}</b></label>
                            <input type="text" name="password" id="password" class="form-control @error('password') is-invalid  @enderror" placeholder="{{ __('Enter Company password') }}" required />
                        </div>
                    </div>
                </div>

                <div class="col-md-12 d-flex justify-content-end">
                    <div class="btn-loading">

                        <button type="button" id="delete-loading-button" class="btn btn-sm btn-danger" style="display: none;">{{ __('Loading...') }}</button>
                        <button type="button" id="delete-button" class="btn btn-sm btn-danger">{{ __('Delete Confirm') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#delete-button').click(function(e) {

                    if (window.confirm('Delete permanently?')) {

                        $('#delete_form').submit();
                    }
                });

                $(document).on('submit', '#delete_form', function(e) {

                    e.preventDefault();

                    var url = $(this).attr('action');
                    var request = $(this).serialize();
                    $('#delete-loading-button').show();
                    $('#delete-button').hide();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: request,
                        success: function(data) {

                            $('#delete-loading-button').hide();
                            $('#delete-button').show();
                            if (!$.isEmptyObject(data.errorMsg)) {

                                toastr.error(data.errorMsg);
                                return;
                            }

                            toastr.success(data);
                            window.location = "{{ url()->previous() }}";
                        },
                        error: function(err) {

                            $('#delete-loading-button').hide();
                            $('#delete-button').show();
                            if (err.status == 0) {

                                toastr.error("{{ __('Net Connection Error.') }}");
                                return;
                            } else if (err.status == 500) {

                                toastr.error("{{ __('Server error. Please contact to the support team.') }}");
                                return;
                            } else if (err.status == 403) {

                                toastr.error("{{ __('Access Denied') }}");
                                return;
                            }

                            toastr.error(err.responseJSON.message);
                        }
                    });
                });
            });
        </script>
    @endpush
</x-saas::admin-layout>
