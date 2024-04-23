<x-saas::admin-layout title="Edit Role">
    <style>
        p.checkbox_input_wrap {
            margin: 0px;
            line-height: 1;
            font-size: 12px;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>{{ __('Edit Role') }}</h5>
                    <div class="btn-box">
                        <a href="{{ route('saas.roles.index') }}" class="btn btn-sm btn-primary">{{ __('All Roles') }}</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('saas.roles.update', $role->id) }}" id="roleStoreForm" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row g-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <label for="name" class="form-label"><strong>{{ __('Role Name') }}</strong><span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('Enter role name') }}" required value="{{ $role->name }}">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-check my-3">
                                    <input type="checkbox" class="permissions form-check-input" id="select-all">
                                    <label class="form-check-label" for="select-all"><b>{{ __('Permissions') }}</b></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info checkbox_input_wrap">
                                    <label>
                                        <input id="select_all" type="checkbox" class="customers select_all" data-target="customers" autocomplete="off">
                                        <strong> {{ __('Customers') }}</strong>
                                    </label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_index')) name="tenants_index" id="tenants_index" class="customers select_all">
                                    <label for="tenants_index"> {{ __('Customer List') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_show')) name="tenants_show" id="tenants_show" class="customers select_all">
                                    <label for="tenants_show"> {{ __('Customer View') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_create')) name="tenants_create" id="tenants_create" class="customers select_all">
                                    <label for="tenants_create">{{ __('Customer Add') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_upgrade_plan')) name="tenants_upgrade_plan" id="tenants_upgrade_plan" class="customers select_all">
                                    <label for="tenants_destroy">{{ __('Customer Upgrade Plan') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_update_payment_status')) name="tenants_update_payment_status" id="tenants_update_payment_status" class="customers select_all">
                                    <label for="tenants_destroy">{{ __('Customer Update Payment Status') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_update_expire_date')) name="tenants_update_expire_date" id="tenants_update_expire_date" class="customers select_all">
                                    <label for="tenants_destroy">{{ __('Customer Update Expire Date') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('tenants_destroy')) name="tenants_destroy" id="tenants_destroy" class="customers select_all">
                                    <label for="tenants_destroy">{{ __('Customer Delete') }}</label>
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info checkbox_input_wrap">
                                    <label>
                                        <input id="select_all" type="checkbox" class="users" data-target="users" autocomplete="off">
                                        <strong>{{ __('Users') }}</strong>
                                    </label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('users_index')) name="users_index" id="users_index" class="users select_all">
                                    <label for="users_index">{{ __('User List') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('users_create')) name="users_create" id="users_create" class="users select_all">
                                    <label for="users_create"> {{ __('User Add') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('users_show')) name="users_show" id="users_show" class="users select_all">
                                    <label for="users_show">{{ __('User View') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('users_update')) name="users_update" id="users_update" class="users select_all">
                                    <label for="users_update"> {{ __('User Edit') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('users_destroy')) name="users_destroy" id="users_destroy" class="users select_all">
                                    <label for="users_destroy"> {{ __('User Delete') }}</label>
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info checkbox_input_wrap">
                                    <label>
                                        <input id="select_all" type="checkbox" class="roles select_all" data-target="roles" autocomplete="off">
                                        <strong>{{ __('Roles') }}</strong>
                                    </label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('roles_index')) name="roles_index" id="roles_index" class="roles select_all">
                                    <label for="roles_index">{{ __('Role List') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('roles_create')) name="roles_create" id="roles_create" class="roles select_all">
                                    <label for="roles_create"> {{ __('Role Add') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('roles_update')) name="roles_update" id="roles_update" class="roles select_all">
                                    <label for="roles_update">{{ __('Role Edit') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('roles_destroy')) name="roles_destroy" id="roles_destroy" class="roles select_all">
                                    <label for="roles_destroy"> {{ __('Role Delete') }}</label>
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info checkbox_input_wrap">
                                    <label>
                                        <input id="select_all" type="checkbox" class="plans select_all" data-target="plans" autocomplete="off">
                                        <strong>{{ __('Plans') }}</strong>
                                    </label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('plans_index')) name="plans_index" id="plans_index" class="plans select_all">
                                    <label for="plans_index">{{ __('Plan List') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('plans_create')) name="plans_create" id="plans_create" class="plans select_all">
                                    <label for="plans_create"> {{ __('Plan Add') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('plans_update')) name="plans_update" id="plans_update" class="plans select_all">
                                    <label for="plans_update">{{ __('Plan Edit') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('plans_destroy')) name="plans_destroy" id="plans_destroy" class="plans select_all">
                                    <label for="plans_destroy"> {{ __('Plan Delete') }}</label>
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info checkbox_input_wrap">
                                    <label>
                                        <input id="select_all" type="checkbox" class="coupons select_all" data-target="coupons" autocomplete="off">
                                        <strong>{{ __('Coupons') }}</strong>
                                    </label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('coupons_index')) name="coupons_index" id="coupons_index" class="coupons select_all">
                                    <label for="coupons_index">{{ __('Coupon List') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('coupons_create')) name="coupons_create" id="coupons_create" class="coupons select_all">
                                    <label for="coupons_create"> {{ __('Coupon Add') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('coupons_update')) name="coupons_update" id="coupons_update" class="coupons select_all">
                                    <label for="coupons_update">{{ __('Coupon Edit') }}</label>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" @checked($role->hasPermissionTo('coupons_destroy')) name="coupons_destroy" id="coupons_destroy" class="coupons select_all">
                                    <label for="coupons_destroy"> {{ __('Coupon Delete') }}</label>
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12 text-end">
                                <input type="submit" class="btn btn-sm btn-primary mt-3" value="{{ __('Update Role') }}" />
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
