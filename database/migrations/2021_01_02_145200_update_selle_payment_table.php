<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSellePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_payment_setting', function (Blueprint $table) {
            $table->string('razorpay_key');
            $table->string('razorpay_secret');
            $table->enum('razorpay_status', [0,1])->default(0);
            $table->string('stripe_key');
            $table->string('stripe_secret');
            $table->enum('stripe_status', [0,1])->default(0);
            $table->string('instamojo_key');
            $table->string('instamojo_token');
            $table->enum('instamojo_status', [0,1])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE seller_payment_setting DROP razorpay_key");
        DB::statement("ALTER TABLE seller_payment_setting DROP razorpay_secret");
        DB::statement("ALTER TABLE seller_payment_setting DROP razorpay_status");
        DB::statement("ALTER TABLE seller_payment_setting DROP stripe_key");
        DB::statement("ALTER TABLE seller_payment_setting DROP stripe_secret");
        DB::statement("ALTER TABLE seller_payment_setting DROP stripe_status");
        DB::statement("ALTER TABLE seller_payment_setting DROP instamojo_key");
        DB::statement("ALTER TABLE seller_payment_setting DROP instamojo_token");
        DB::statement("ALTER TABLE seller_payment_setting DROP instamojo_status");
    }
}
