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
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropForeign('exchange_rates_currency_id_foreign');
            $table->dropIndex('date');
            $table->unique(['date', 'currency_id']);
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropIndex('exchange_rates_date_currency_id_unique');
            $table->unique('currency_id', 'date');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }
};
