<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id');
            $table->string('contact_name');
            $table->string('company_name');
            $table->string('address_1');
            $table->string('address_2');
            $table->string('address_3');
            $table->string('state');
            $table->string('city');
            $table->string('zip_code');
            $table->string('country');
            $table->string('email');
            $table->string('phone');
            $table->enum('is_hide_phone', [0,1])->default(0);
            $table->enum('is_hide_address', [0,1])->default(0);
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
        Schema::dropIfExists('contact_us');
    }
}
