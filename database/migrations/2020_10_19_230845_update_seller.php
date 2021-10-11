<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('sellers', function (Blueprint $table) {
        $table->string('gst_number');
        $table->string('cin_number');
        $table->string('aadhar_number');
        $table->string('pan_number');
        $table->string('business_proof');
        $table->enum('kyc_status', ['submitted', 'pending', 'rejected','verified'])->default('pending');
      });
      DB::statement("ALTER TABLE sellers ADD aadhar_img MEDIUMBLOB");
      DB::statement("ALTER TABLE sellers ADD pan_img MEDIUMBLOB");
      DB::statement("ALTER TABLE sellers ADD tax_proof_img MEDIUMBLOB");
      DB::statement("ALTER TABLE sellers ADD age_proof_img MEDIUMBLOB");
      DB::statement("ALTER TABLE sellers ADD address_proof_img MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::statement("ALTER TABLE sellers DROP aadhar_img");
      DB::statement("ALTER TABLE sellers DROP pan_img");
      DB::statement("ALTER TABLE sellers DROP tax_proof_img");
      DB::statement("ALTER TABLE sellers DROP age_proof_img");
      DB::statement("ALTER TABLE sellers DROP address_proof_img");
      DB::statement("ALTER TABLE sellers DROP business_proof");
      DB::statement("ALTER TABLE sellers DROP gst_number");
      DB::statement("ALTER TABLE sellers DROP cin_number");
      DB::statement("ALTER TABLE sellers DROP aadhar_number");
      DB::statement("ALTER TABLE sellers DROP pan_number");
      DB::statement("ALTER TABLE sellers DROP kyc_status");
    }
}
