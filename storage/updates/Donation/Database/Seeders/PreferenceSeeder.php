<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Preference::insert([
            [
                'category' => 'donation',
                'field' => 'donation_fee_applicable',
                'value' => 'yes'
            ],
            [
                'category' => 'donation',
                'field' => 'donation_available_for',
                'value' => 'both'
            ]
        ]);
    }
}
