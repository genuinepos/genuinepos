<?php

namespace Modules\SAAS\Console;

use Illuminate\Console\Command;
use Modules\SAAS\Database\Seeders\RolePermissionTableSeeder;
use Modules\SAAS\Entities\Permission;
use Modules\SAAS\Entities\Role;

class RolePermissionSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'saas:rp-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Role Permission Sync';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roles = (new RolePermissionTableSeeder)->rolesArray();
        $permissions = (new RolePermissionTableSeeder)->permissionsArray();
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (! isset($role)) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
                echo 'Role Created: '.$roleName."\n";
            }
        }
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if (! isset($permission)) {
                Permission::create(['name' => $permissionName, 'guard_name' => 'web']);
                echo 'Permission Created: '.$permissionName."\n";
            }
        }

        $admin = Role::where('name', 'admin')->first();
        $admin->permissions()->sync(Permission::all());
        echo "Role Permission Synced\n";
    }
}
