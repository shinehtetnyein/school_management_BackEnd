<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GenerateRolesAndPermissions extends Command
{
    protected $signature = 'app:generate-roles-and-permissions';
    protected $description = 'Generate roles and permissions based on SMS class diagram configuration';

    public function handle()
    {
        $this->info('Starting roles and permissions generation...');

        $this->disableForeignKeyChecks();
        $this->truncatePermissionAndRoleTables();
        $this->enableForeignKeyChecks();

        $this->generatePermissions();
        $this->generateRoles();

        $this->info('Roles and permissions generation completed!');
        app(PermissionRegistrar::class)->forgetCachedPermissions(); // Clear cache
    }

    private function disableForeignKeyChecks(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }

    private function enableForeignKeyChecks(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function truncatePermissionAndRoleTables(): void
    {
        $tables = ['role_has_permissions', 'model_has_permissions', 'model_has_roles'];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        Role::truncate();
        Permission::truncate();
    }

    private function generatePermissions(): void
    {
        $config = config('permissions', []); // Use 'permissions' config from earlier
        $defaultGuard = $config['default-guard'] ?? 'api';
        $commonActions = $config['common-actions'] ?? ['view', 'create', 'edit', 'delete'];
        $permissionsFromConfig = $config['permissions'] ?? [];

        $permissionRecords = [];

        foreach ($permissionsFromConfig as $entity => $setting) {
            $guardName = $setting['guard'] ?? $defaultGuard;

            // Add common actions if enabled
            if (!empty($setting['common-actions'])) {
                foreach ($commonActions as $action) {
                    $permissionRecords[] = ['name' => "{$entity}.{$action}", 'guard_name' => $guardName];
                }
            }

            // Add custom actions
            foreach ($setting['actions'] ?? [] as $action) {
                $permissionRecords[] = ['name' => "{$entity}.{$action}", 'guard_name' => $guardName];
            }
        }

        if (!empty($permissionRecords)) {
            Permission::insert($permissionRecords);
            $this->info(count($permissionRecords) . " permissions created successfully.");
        } else {
            $this->warn('No permissions to create.');
        }
    }

    private function generateRoles(): void
    {
        $config = config('permissions', []);
        $defaultGuard = $config['default-guard'] ?? 'api';
        $rolesFromConfig = $config['roles'] ?? [];

        foreach ($rolesFromConfig as $roleName => $setting) {
            $guardName = $setting['guard_name'] ?? $defaultGuard;
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => $guardName]);

            $permissions = $this->expandWildcards($setting['permissions'] ?? [], $role->guard_name);
            $role->syncPermissions($permissions);

            $this->info("{$role->name} role created/updated with " . count($permissions) . " permission(s).");
        }
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
