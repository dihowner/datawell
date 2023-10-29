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
        Schema::create('airtime_requests', function (Blueprint $table) {
            $table->id();
            $table->string('product_id', 50);
            $table->string('init_code', 50)->nullable();
            $table->string('wrap_code', 50)->nullable();
            $table->string('mobilenig', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airtime_requests');
    }
};