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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->integer('file_id')->unsigned()->index('donations_file_id_idx')->nullable();
            $table->integer('currency_id')->unsigned()->index('donations_currency_id_idx');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('restrict');
            $table->integer('creator_id')->unsigned()->index('donations_creator_id_idx');
            $table->foreign('creator_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('fee_bearer')->default('donor')->comment('donor or creator');
            $table->string('display_brand_image', 5)->default('yes')->comment('yes or no');
            $table->string('title', 150)->index('donations_title_idx');
            $table->string('slug')->index('donations_slug_idx')->unique();
            $table->text('description')->comment('donation description');
            $table->decimal('goal_amount', 20, 8)->comment('goal amount of the donation event');
            $table->decimal('raised_amount', 20, 8)->comment('upto raised amount');
            $table->string('donation_type', 30)->default('any_amount')->comment('any_amount, fixed_amount or suggested_amount')->index('donations_donation_type_idx');
            $table->decimal('fixed_amount', 20, 8)->nullable()->comment('value of donation type fixed amount');
            $table->decimal('first_suggested_amount', 20, 8)->nullable()->comment('first suggested amount from donation creator');
            $table->decimal('second_suggested_amount', 20, 8)->nullable()->comment('second suggested amount from donation creator');
            $table->decimal('third_suggested_amount', 20, 8)->nullable()->comment('third suggested amount from donation creator');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
