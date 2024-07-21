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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->default('')->unique();
            $table->string('password');
            $table->string('salt', 40)->default('');
            $table->integer('premdays')->default(0);
            $table->integer('lastday')->unsigned()->default(0);
            $table->string('key', 32)->default('0');
            $table->boolean('blocked')->default(0)->comment('internal usage');
            $table->integer('warnings')->default(0);
            $table->integer('group_id')->default(1);
            $table->string('email')->unique();
            $table->string('email_new')->default('');
            $table->integer('email_new_time')->default(0);
            $table->string('rlname');
            $table->string('location');
            $table->integer('page_access');
            $table->string('email_code');
            $table->integer('next_email');
            $table->integer('premium_points')->default(0);
            $table->integer('create_date')->default(0);
            $table->integer('create_ip')->unsigned()->default(0);
            $table->integer('last_post')->default(0);
            $table->string('flag', 80);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
