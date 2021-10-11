<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerKycTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_kyc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id');
            $table->integer('aadhar_verified');
            $table->integer('pan_verified');
            $table->integer('aadhar_pre_verified');
            $table->integer('pan_pre_verified');
            $table->integer('gst_verified');
            $table->integer('cin_verified');
            $table->integer('age_proof_verified');
            $table->integer('address_proof_verified');
            $table->integer('tax_verified');
            $table->integer('business_verified');
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
        Schema::dropIfExists('seller_kyc');
    }
}
