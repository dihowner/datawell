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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('description');
            $table->float('old_balance')->default(0);
            $table->float('amount')->default(0);
            $table->float('new_balance')->default(0);
            $table->longText('bank_info')->nullable();
            $table->longText('remark')->nullable();
            $table->enum("status", [0, 1, 2])->default(0);
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
        Schema::dropIfExists('withdrawals');
    }
};