<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kyc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nin_name')->nullable();
            $table->string('nin_number', 50)->nullable();
            $table->enum('nin_status', ['pending', 'verified'])->default('pending');
            $table->longText('nin_response')->comment('verification response')->nullable();
            $table->longText('nin_data')->nullable();
            $table->timestamp('nin_date_verified')->nullable();
            $table->string('bvn_name')->nullable();
            $table->string('bvn_number', 50)->nullable();
            $table->enum('bvn_status', ['pending', 'verified'])->default('pending');
            $table->longText('bvn_response')->comment('verification response')->nullable();
            $table->longText('bvn_data')->nullable();
            $table->timestamp('bvn_date_verified')->nullable();
            $table->foreign('user_id')->references('id')->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kyc');
    }
};
