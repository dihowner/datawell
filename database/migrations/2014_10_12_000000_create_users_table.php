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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('fullname');
            $table->string('phone_number')->unique();
            $table->string('emailaddress')->unique();
            $table->string('password');
            $table->tinyInteger('plan_id')->default(0);
            $table->string('secret_pin', 25)->default('0000');
            $table->string('auto_funding_reference')->nullable();
            $table->enum('is_verified', [0, 1])->default('0');
            $table->rememberToken();
            $table->timestamps();
        });
        
        Schema::create('user_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('value');
            $table->foreign('user_id')->references('id')->on("users")->onDelete("cascade");
            $table->timestamp('date_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_metas');
    }
};