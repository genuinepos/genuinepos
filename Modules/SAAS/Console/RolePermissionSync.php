<?php

namespace Modules\SAAS\Console;

use Illuminate\Console\Command;
use Modules\SAAS\Entities\Role;
use Illuminate\Support\Facades\DB;
use Modules\SAAS\Entities\Permission;
use Illuminate\Support\Facades\Schema;
use Modules\SAAS\Database\Seeders\RolePermissionTableSeeder;

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
        $this->truncateRolePermissionDataButKeepOldData();
        $roles = (new RolePermissionTableSeeder)->rolesArray();
        $permissions = (new RolePermissionTableSeeder)->permissionsArray();

        foreach ($roles as $roleName) {

            $role = Role::where('name', $roleName)->first();
            if (!isset($role)) {

                Role::create(['name' => $roleName, 'guard_name' => 'web']);
                echo 'Role Created: ' . $roleName . "\n";
            }
        }

        foreach ($permissions as $permission) {

            $exists = Permission::where('name', $permission['name'])->first();

            if (!isset($exists)) {

                Permission::create(['id' => $permission['id'], 'name' => $permission['name'], 'guard_name' => 'web']);
                echo 'Permission Created: ' . $permission['name'] . "\n";
            }
        }

        $admin = Role::where('name', 'admin')->first();
        $admin->permissions()->sync(Permission::all());
        echo "Role Permission Synced\n";
    }

    private function truncateRolePermissionDataButKeepOldData(): void
    {
        // echo 'Truncate Start'.PHP_EOL;
        Schema::disableForeignKeyConstraints();

        if (Role::count() == 0) {

            DB::statement('ALTER TABLE `roles` AUTO_INCREMENT = 1');
        }

        Permission::truncate();
        if (Permission::count() == 0) {

            \Illuminate\Support\Facades\DB::statement('ALTER TABLE `permissions` AUTO_INCREMENT = 1');
        }

        Schema::enableForeignKeyConstraints();
        // echo 'Truncate Completed'.PHP_EOL;
    }
}
