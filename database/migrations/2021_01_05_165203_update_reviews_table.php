<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function(Blueprint $table) {
            DB::statement("ALTER TABLE reviews DROP rating");
            $table->integer('rating_delivery');
            $table->integer('rating_price');
            $table->integer('rating_value');
            $table->integer('rating_quality');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function(Blueprint $table) {
            $table->integer('rating');
            DB::statement("ALTER TABLE reviews DROP rating_delivery");
            DB::statement("ALTER TABLE reviews DROP rating_price");
            DB::statement("ALTER TABLE reviews DROP rating_value");
            DB::statement("ALTER TABLE reviews DROP rating_quality");
        });
    }
}
