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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("plan");
            $table->string("description");
            $table->json("extra_info");
            $table->string("destination", 100)->nullable();
            $table->float("old_balance", 8, 2)->default(0);
            $table->float("amount", 8, 2)->default(0);
            $table->float("new_balance", 8, 2)->default(0);
            $table->float("costprice", 8, 2)->default(0);
            $table->enum("category", ['airtime', 'data', 'cabletv', 'education', 'electricity', 'plan upgrade'])->default('airtime');
            $table->string("transaction_reference")->comment("provider refernce")->nullable();
            $table->string("reference");
            $table->json("pin_details")->nullable();
            $table->json("token_details")->nullable();
            $table->longText("response")->nullable();
            $table->json("memo")->nullable();
            $table->enum("status", [0, 1, 2, 3, 4])->default(0);
            $table->string("ussd_code", 100)->nullable();
            $table->unsignedInteger("api_id");
            $table->enum("channel", ['website', 'api'])->default('website');
            $table->foreign('user_id')->references('id')->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};