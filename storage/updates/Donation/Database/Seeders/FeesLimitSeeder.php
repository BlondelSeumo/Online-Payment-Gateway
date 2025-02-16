<?php

namespace Modules\Donation\Database\Seeders;

use Illuminate\Database\Seeder;

class FeesLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionType = \App\Models\TransactionType::where('name', 'Donation_Sent')->first();
        $currency = \App\Models\Currency::where('default', 1)->first();

        \App\Models\FeesLimit::insert([
            'currency_id' => $currency->id,
            'transaction_type_id' => $transactionType->id,
            'payment_method_id' => Mts,
            'charge_percentage' => 0,
            'charge_fixed' => 0,
            'min_limit' => 1,
            'max_limit' => null,
            'processing_time' => 0,
            'has_transaction' => 'Yes',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
