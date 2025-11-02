<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding roles and permissions from config/role&permission.php');

        $config = config('role&permission', []);
        $defaultGuard = $config['default-guard'] ?? 'api';
        $commonActions = $config['common-actions'] ?? ['view', 'create', 'edit', 'delete'];
        $permissionsFromConfig = $config['permissions'] ?? [];

        // Forget cached permissions
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Safely clear tables by disabling foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        Permission::truncate();
        Role::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create permissions
        $permissionRecords = [];
        foreach ($permissionsFromConfig as $entity => $setting) {
            $guardName = $setting['guard'] ?? $defaultGuard;

            if (!empty($setting['common-actions'])) {
                foreach ($commonActions as $action) {
                    $permissionRecords[] = ['name' => "{$entity}.{$action}", 'guard_name' => $guardName];
                }
            }

            foreach ($setting['actions'] ?? [] as $action) {
                $permissionRecords[] = ['name' => "{$entity}.{$action}", 'guard_name' => $guardName];
            }
        }

        if (!empty($permissionRecords)) {
            Permission::insert($permissionRecords);
            $this->command->info(count($permissionRecords) . ' permissions created.');
        }

        // Create roles and assign permissions
        $rolesFromConfig = $config['roles'] ?? [];
        foreach ($rolesFromConfig as $roleName => $setting) {
            $guardName = $setting['guard_name'] ?? $defaultGuard;
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guardName]);

            $permissions = $this->expandWildcards($setting['permissions'] ?? [], $guardName);
            $role->syncPermissions($permissions);
            $this->command->info("Role {$roleName} created with " . count($permissions) . " permissions.");
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function expandWildcards(array $permissions, string $guardName): array
    {
        $allPermissions = Permission::where('guard_name', $guardName)->pluck('name')->toArray();
        $expanded = [];

        foreach ($permissions as $perm) {
            if (str_contains($perm, '.*')) {
                $entity = explode('.', $perm)[0];
                $entityPerms = array_filter($allPermissions, fn($p) => str_starts_with($p, "{$entity}."));
                $expanded = array_merge($expanded, array_values($entityPerms));
            } else {
                $expanded[] = $perm;
            }
        }

        return array_unique($expanded);
    }
}
