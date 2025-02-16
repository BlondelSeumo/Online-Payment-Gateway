<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DonationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(MetaSeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(TransactionTypeSeeder::class);
        $this->call(PreferenceSeeder::class);
        $this->call(FeesLimitSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PermissionRoleSeeder::class);
    }
}
