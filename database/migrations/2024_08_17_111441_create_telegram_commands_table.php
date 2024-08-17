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
        Schema::create('telegram_next_commands', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('telegram_user_id');
            $table->bigInteger('telegram_chat_id');
            $table->string('command');
            $table->timestamps();
            $table->unique(['telegram_user_id', 'telegram_chat_id']);
            $table->foreign('telegram_user_id')->references('id')->on('telegram_users');
            $table->foreign('telegram_chat_id')->references('id')->on('telegram_chats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_commands');
    }
};
