<x-saas::admin-layout title="coupons">
    @push('css')
        <style>

        </style>
    @endpush
    <div class="panel">
        <div class="panel-header">
            <h5>{{ __('Email Settings') }}</h5>
            <div>
                <a href="{{route('saas.email-settings.create')}}" class="btn btn-sm btn-primary">{{ __('Create Settings') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col table-responsive">
                    <table class="table table-dashed table-hover digi-dataTable all-product-table table-striped" id="userTable">
                        <thead>
                            <tr>
                                <th>{{ __('SL No.') }}</th>
                                <th>{{ __('Provider Name') }}</th>
                                <th>{{ __('Mail Mailer') }}</th>
                                <th>{{ __('Mail Host') }}</th>
                                <th>{{ __('Mail Port') }}</th>
                                <th>{{ __('Mail Username') }}</th>
                                <th>{{ __('Mail Password') }}</th>
                                <th>{{ __('Mail Encryption') }}</th>
                                <th>{{ __('Mail From Address') }}</th>
                                <th>{{ __('Mail From Name') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            var table = $("#userTable").DataTable({
                ajax: {
                    url: "{{ route('saas.email-settings.index') }}",
                    type: 'GET'
                },
                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex'
                    },
                    {
                        name: 'provider_name',
                        data: 'provider_name'
                    },
                    {
                        name: 'mail_mailer',
                        data: 'mail_mailer'
                    },
                    {
                        name: 'mail_host',
                        data: 'mail_host'
                    },
                    {
                        name: 'mail_port',
                        data: 'mail_port'
                    },
                    {
                        name: 'mail_username',
                        data: 'mail_username'
                    },

                    {
                        name: 'mail_password',
                        data: 'mail_password'
                    },

                    {
                        name: 'mail_encryption',
                        data: 'mail_encryption'
                    },


                    {
                        name: 'mail_from_address',
                        data: 'mail_from_address'
                    },

                    {
                        name: 'mail_from_name',
                        data: 'mail_from_name'
                    },

                    {
                        name: 'mail_active',
                        data: 'mail_active'
                    },

                    {
                        name: 'action',
                        data: 'action'
                    }
                ],
            });
        </script>
    @endpush
</x-saas::admin-layout>
