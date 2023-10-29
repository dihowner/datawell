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
        Schema::create('app_servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_id');
            $table->string('category');
            $table->unsignedSmallInteger('calling_time');
            $table->unsignedTinyInteger('app_color_scheme');
            $table->string('auth_code')->nullable();
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
        Schema::dropIfExists('app_servers');
    }
};