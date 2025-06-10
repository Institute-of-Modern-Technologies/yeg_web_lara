<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if any columns need to be added to the existing payments table
        // The first migration already created the table with all needed columns
        // This migration is now just a placeholder
        if (Schema::hasTable('payments')) {
            // Only add columns that don't exist in the first migration if needed
            // For example, if any new fields are required later
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
