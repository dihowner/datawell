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
        Schema::create('airtime_to_cash', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('airtime_reciever', 50)->nullable();
            $table->string('airtime_sender', 50)->nullable();
            $table->float("airtime_amount")->default(0);
            $table->float("old_balance")->default(0);
            $table->float("amount")->default(0);
            $table->float("new_balance")->default(0);
            $table->enum('network', ['mtn','airtel','glo','9mobile','refund'])->default('mtn');
            $table->longText('additional_note')->nullable();
            $table->enum("status", [0, 1, 2])->default(0);
            $table->string("reference")->nullable();
            $table->longText('remark')->nullable();
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
        Schema::dropIfExists('airtime_to_cash');
    }
};