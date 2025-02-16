<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id')->unsigned()->index('donation_payments_currency_id_idx')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->bigInteger('payer_id')->index('donation_payments_payer_id_idx');
            $table->foreignId('donation_id')->constrained()->onUpdate('cascade')->onDelete('cascade')->index('donation_payments_donation_id_idx');
            $table->integer('payment_method_id')->unsigned()->index('donation_payments_payment_method_id_idx')->nullable();
            $table->string('uuid', 13)->unique()->comment("unique id for each donation payment");
            $table->decimal('amount', 20, 8)->comment('amount except fees');
            $table->decimal('charge_percentage', 20, 8)->comment('charge percentage amount in %');
            $table->decimal('charge_fixed', 20, 8)->comment('charge fixed amount');
            $table->decimal('total', 20, 8)->comment('amount plus fees');
            $table->string('status', 10)->default('Pending')->comment('Pending or Success or Cancelled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_payments');
    }
};
