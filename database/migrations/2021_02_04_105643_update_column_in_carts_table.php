<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnInCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
                $table->integer('seller_id');
                $table->integer('mail_1');
                $table->integer('mail_3');
                $table->integer('mail_7');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE carts DROP seller_id");
        DB::statement("ALTER TABLE carts DROP mail_1");
        DB::statement("ALTER TABLE carts DROP mail_3");
        DB::statement("ALTER TABLE carts DROP mail_7");
    }
}
