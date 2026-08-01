<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')
            ->whereNull('company_id')
            ->first();

        $admin = User::firstOrCreate(
            ['email' => 'admin@yourcompany.com'], // <-- চাইলে বদলে নিন
            [
                'name' => 'Super Admin',
                'password' => Hash::make('ChangeMe123!'), // <-- login করেই বদলে ফেলুন
                'is_active' => true,
                'company_id' => null,
                'branch_id' => null,
            ]
        );

        if ($superAdminRole && ! $admin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $admin->roles()->attach($superAdminRole->id);
        }
    }
}