<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
           $table->string('domain'); 
           $table->longText('about'); 
           $table->string('country'); 
           $table->string('state'); 
           $table->string('city');
           $table->string('company_name');
           $table->longText('refund_policy');
           $table->longText('shipping_policy');
           $table->longText('payment_policy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       DB::statement("ALTER TABLE shops DROP domain");
       DB::statement("ALTER TABLE shops DROP about");
       DB::statement("ALTER TABLE shops DROP country");
       DB::statement("ALTER TABLE shops DROP state");
       DB::statement("ALTER TABLE shops DROP city");
       DB::statement("ALTER TABLE shops DROP company_name");
       DB::statement("ALTER TABLE shops DROP refund_policy");
       DB::statement("ALTER TABLE shops DROP shipping_policy");
       DB::statement("ALTER TABLE shops DROP payment_policy");
    }
}
