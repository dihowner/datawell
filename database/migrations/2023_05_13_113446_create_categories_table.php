<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->string('parent_category')->nullable();
        });

        // Insert some stuff
        $categoryArray = [
            "Airtime Topup",
            "MTN Data Bundle",
            "MTN SME Data",
            "MTN CG Data",
            "MTN Direct Data",
            "Cable TV",
            "Electricity",
            "Education"
        ];

        $this->loadCategories($categoryArray);
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }

    // Loading some default category    
    private function loadCategories($categoryArray) {
        foreach($categoryArray as $categoryName) {
            DB::table('categories')->insert(['category_name' => $categoryName]);
        }
    }
};