<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminPermissions = \App\Models\Permission::whereIn('group', config('donation.permission_group'))
            ->where('user_type', 'Admin')
            ->get();

        foreach ($adminPermissions as $value) {
            if ($value->display_name == null) {
                continue;
            }
            
            $roleData[] = [
                'role_id' => 1,
                'permission_id' => $value->id,
            ];
        }

        $userPermissions = \App\Models\Permission::whereIn('group', config('donation.permission_group'))
            ->where('user_type', 'User')
            ->get();
            
        foreach ($userPermissions as $value) {
            $roleData[] = [
                'role_id' => 2,
                'permission_id' => $value->id,
            ];
            $roleData[] = [
                'role_id' => 3,
                'permission_id' => $value->id,
            ];
        }

        \App\Models\PermissionRole::insert($roleData);
    }
}
