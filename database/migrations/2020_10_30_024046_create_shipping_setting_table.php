<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id');
            $table->string('rate_per_product');
            $table->enum('shipping_type',['local', 'regional', 'national', 'international']);
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
        Schema::dropIfExists('shipping_setting');
    }
}
