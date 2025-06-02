<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->nullable();
            }
            
            if (!Schema::hasColumn('students', 'registration_number')) {
                $table->string('registration_number')->nullable()->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['name', 'first_name', 'last_name', 'full_name', 'registration_number']);
        });
    }
}
