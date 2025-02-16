<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class MetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metas = [
            ['url' => 'campaigns', 'title' => 'Campaigns', 'description' => 'Campaigns', 'keywords' => ''],
            ['url' => 'campaigns/{slug}', 'title' => 'Campaign Details', 'description' => 'Campaign Details', 'keywords' => ''],
            ['url' => 'user/campaigns', 'title' => 'Campaigns', 'description' => 'Campaigns', 'keywords' => ''],
            ['url' => 'user/campaigns/create', 'title' => 'Add Campaign', 'description' => 'Campaign', 'keywords' => ''],
            ['url' => 'user/campaigns/edit/{donation}', 'title' => 'Edit Campaign', 'description' => 'Edit Campaign', 'keywords' => ''],
            ['url' => 'user/campaigns/detail/{donation}', 'title' => 'Campaign Detail', 'description' => 'Campaign Detail', 'keywords' => ''],
            ['url' => 'user/campaign-payments', 'title' => 'Campaign Payments', 'description' => 'Campaign Payments', 'keywords' => '']
        ];

        \App\Models\Meta::insert($metas);
    }
}
