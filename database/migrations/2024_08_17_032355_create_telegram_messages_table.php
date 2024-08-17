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
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('telegram_user_id');
            $table->bigInteger('chat_id');
            $table->text('text');
            $table->bigInteger('date');
            $table->timestamps();

            $table->foreign('telegram_user_id')->references('id')->on('telegram_users');
            $table->foreign('chat_id')->references('id')->on('telegram_chats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
