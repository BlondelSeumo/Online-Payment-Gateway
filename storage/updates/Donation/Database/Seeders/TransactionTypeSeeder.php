<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TransactionType::insert(
            [
                ['name' => 'Donation_Sent'],
                ['name' => 'Donation_Received'],
            ]
        );
    }
}
