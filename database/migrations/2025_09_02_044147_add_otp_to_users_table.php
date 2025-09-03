<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_otp_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_hash')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('is_verified')->default(false)->index();
            $table->timestamp('email_verified_at')->nullable(); // optional, useful with Laravel conventions
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_hash','otp_expires_at','is_verified','email_verified_at']);
        });
    }
};
