<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'group' => 'Campaign',
                'name' => 'view_campaign',
                'display_name' => 'View Campaign',
                'description' => 'View Campaign',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign',
                'name' => 'add_campaign',
                'display_name' => null,
                'description' => null,
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign',
                'name' => 'edit_campaign',
                'display_name' => 'Edit Campaign',
                'description' => 'Edit Campaign',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign',
                'name' => 'delete_campaign',
                'display_name' => 'Delete Campaign',
                'description' => 'Delete Campaign',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Payment',
                'name' => 'view_campaign_payment',
                'display_name' => 'View Campaign Payment',
                'description' => 'View Campaign Payment',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Payment',
                'name' => 'add_campaign_payment',
                'display_name' => null,
                'description' => null,
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Payment',
                'name' => 'edit_campaign_payment',
                'display_name' => 'Edit Campaign Payment',
                'description' => 'Edit Campaign Payment',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Payment',
                'name' => 'delete_campaign_payment',
                'display_name' => null,
                'description' => null,
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Setting',
                'name' => 'view_campaign_setting',
                'display_name' => 'View Campaign Setting',
                'description' => 'View Campaign Setting',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Setting',
                'name' => 'add_campaign_setting',
                'display_name' => 'Add Campaign Setting',
                'description' => 'Add Campaign Setting',
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Setting',
                'name' => 'edit_campaign_setting',
                'display_name' => null,
                'description' => null,
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign Setting',
                'name' => 'delete_campaign_setting',
                'display_name' => null,
                'description' => null,
                'user_type' => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'group' => 'Campaign',
                'name' => 'manage_campaign',
                'display_name' => 'Manage Campaign',
                'description' => 'Manage Campaign',
                'user_type' => 'User',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
       
        \App\Models\Permission::insert($permissions);
    }
}
