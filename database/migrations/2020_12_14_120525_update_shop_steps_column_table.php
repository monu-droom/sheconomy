<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShopStepsColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->enum('step_1',['incomplete', 'complete'])->nullable();
            $table->enum('step_2',['incomplete', 'complete'])->nullable();
            $table->enum('step_3',['incomplete', 'complete'])->nullable();
            $table->enum('step_4',['incomplete', 'complete'])->nullable();
            $table->enum('step_5',['incomplete', 'complete'])->nullable();
            $table->enum('step_6',['incomplete', 'complete'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE shops DROP step_1");
        DB::statement("ALTER TABLE shops DROP step_2");
        DB::statement("ALTER TABLE shops DROP step_3");
        DB::statement("ALTER TABLE shops DROP step_4");
        DB::statement("ALTER TABLE shops DROP step_5");
        DB::statement("ALTER TABLE shops DROP step_6");
    }
}
