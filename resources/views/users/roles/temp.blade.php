<div class="row mt-3">
    <div class="col-md-3">
        <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_components" autocomplete="off">
            <strong>Asset components</strong></p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_components_index" {{ $role->hasPermissionTo('asset_components_index') ? 'checked' : '' }} class="asset_components asset_permission super_select_all"> &nbsp; Asset components list
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_components_create" {{ $role->hasPermissionTo('asset_components_create') ? 'checked' : '' }} class="asset_components asset_permission super_select_all"> &nbsp; Asset components create
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_components_view" {{ $role->hasPermissionTo('asset_components_view') ? 'checked' : '' }} class="asset_components asset_permission super_select_all"> &nbsp; Asset components detail
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_components_update" {{ $role->hasPermissionTo('asset_components_update') ? 'checked' : '' }} class="asset_components asset_permission super_select_all"> &nbsp; Asset components update
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_components_delete" {{ $role->hasPermissionTo('asset_components_delete') ? 'checked' : '' }} class="asset_components asset_permission super_select_all"> &nbsp; Asset components delete
        </p>
    </div>

    <div class="col-md-3">
        <p class="text-info"><input type="checkbox" class="select_all super_select_all asset_permission " data-target="asset_licenses_categories" autocomplete="off">
            <strong>Asset licenses categories</strong></p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_licenses_categories_index" {{ $role->hasPermissionTo('asset_licenses_categories_index') ? 'checked' : '' }} class="asset_licenses_categories asset_permission super_select_all"> &nbsp; Asset licenses categories list
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_licenses_categories_create" {{ $role->hasPermissionTo('asset_licenses_categories_create') ? 'checked' : '' }} class="asset_licenses_categories asset_permission super_select_all"> &nbsp; Asset licenses categories create
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_licenses_categories_view" {{ $role->hasPermissionTo('asset_licenses_categories_view') ? 'checked' : '' }} class="asset_licenses_categories asset_permission super_select_all"> &nbsp; Asset licenses categories detail
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_licenses_categories_update" {{ $role->hasPermissionTo('asset_licenses_categories_update') ? 'checked' : '' }} class="asset_licenses_categories asset_permission super_select_all"> &nbsp; Asset licenses categories update
        </p>
        <p class="checkbox_input_wrap mt-1">
            <input type="checkbox" name="asset_licenses_categories_delete" {{ $role->hasPermissionTo('asset_licenses_categories_delete') ? 'checked' : '' }} class="asset_licenses_categories asset_permission super_select_all"> &nbsp; Asset licenses categories delete
        </p>
    </div>
