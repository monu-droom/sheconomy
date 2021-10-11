<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPaymentSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_payment_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id');
            $table->string('paypal_mid');
            $table->string('paypal_key');
            $table->string('paypal_email');
            $table->enum('payment_status', [0,1])->default(1);
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
        Schema::dropIfExists('seller_payment_setting');
    }
}
