<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionFieldsToFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->decimal('school_commission', 10, 2)->nullable()->after('partner_discount');
            $table->decimal('imt_commission', 10, 2)->nullable()->after('school_commission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
            $table->dropColumn('school_commission');
            $table->dropColumn('imt_commission');
        });
    }
}
