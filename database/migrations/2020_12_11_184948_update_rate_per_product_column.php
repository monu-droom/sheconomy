<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRatePerProductColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_setting', function(Blueprint $table) {
            $table->dropColumn('rate_per_product');
            $table->json('rate_weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_setting', function(Blueprint $table) {
            $table->string('rate_per_product');
            $table->dropColumn('rate_weight');
        });
    }
}
