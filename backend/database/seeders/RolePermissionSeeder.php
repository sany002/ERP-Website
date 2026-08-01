<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * System-wide role templates (company_id = null). When a new company is
     * onboarded, CompanyOnboardingService clones these into company-owned roles.
     */
    private array $roles = [
        'Super Admin', 'Admin', 'Company Owner', 'Manager', 'Accountant', 'Cashier',
        'Garage Supervisor', 'Gate Operator', 'Workshop Supervisor', 'Inventory Manager',
        'Sales Manager', 'Purchase Manager', 'HR Manager', 'Employee', 'Mechanic',
        'Driver', 'Vehicle Owner', 'Customer', 'Supplier', 'Auditor', 'Viewer',
    ];

    /** Core permission set — grows as each module ships. */
    private array $modules = [
        'users', 'roles', 'companies', 'branches', 'vehicles', 'jobs',
        'inventory', 'accounting', 'invoices', 'sales', 'purchases',
        'hr', 'reports', 'settings',
    ];

    private array $actions = ['view', 'create', 'update', 'delete', 'export'];

    public function run(): void
    {
        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                Permission::firstOrCreate(
                    ['name' => "{$module}.{$action}"],
                    ['module' => $module, 'description' => ucfirst($action) . ' ' . ucfirst($module)]
                );
            }
        }

        foreach ($this->roles as $roleName) {
            $role = Role::firstOrCreate(
                ['company_id' => null, 'slug' => Str::slug($roleName)],
                ['name' => $roleName, 'is_system' => true]
            );

            if ($role->slug === 'super-admin' || $role->slug === 'admin') {
                $role->permissions()->sync(Permission::pluck('id'));
            }
        }

        $this->assignWorkshopPermissions();
    }

    /** Wires up sensible defaults for the Phase 1 (workshop) roles. */
    private function assignWorkshopPermissions(): void
    {
        $grants = [
            'company-owner' => ['vehicles' => ['view', 'create', 'update', 'delete'], 'jobs' => ['view', 'create', 'update', 'delete']],
            'garage-supervisor' => ['vehicles' => ['view', 'create', 'update'], 'jobs' => ['view', 'create', 'update', 'delete']],
            'workshop-supervisor' => ['vehicles' => ['view'], 'jobs' => ['view', 'create', 'update']],
            'gate-operator' => ['vehicles' => ['view', 'create'], 'jobs' => ['view', 'create']],
            'mechanic' => ['vehicles' => ['view'], 'jobs' => ['view', 'update']],
            'vehicle-owner' => ['vehicles' => ['view']],
            'customer' => ['vehicles' => ['view']],
            'auditor' => ['vehicles' => ['view'], 'jobs' => ['view']],
            'viewer' => ['vehicles' => ['view'], 'jobs' => ['view']],
        ];

        foreach ($grants as $slug => $modules) {
            $role = Role::where('company_id', null)->where('slug', $slug)->first();
            if (!$role) {
                continue;
            }

            $names = [];
            foreach ($modules as $module => $actions) {
                foreach ($actions as $action) {
                    $names[] = "{$module}.{$action}";
                }
            }

            $ids = Permission::whereIn('name', $names)->pluck('id');
            $role->permissions()->syncWithoutDetaching($ids);
        }
    }
}
