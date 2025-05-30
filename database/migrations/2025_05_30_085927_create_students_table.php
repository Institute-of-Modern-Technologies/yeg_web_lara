<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->integer('age');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('parent_contact');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('set null');
            $table->string('city');
            $table->foreignId('program_type_id')->constrained()->onDelete('cascade');
            $table->string('payment_reference')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'school_sponsored'])->default('pending');
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->string('payer_type')->default('individual'); // Can be 'individual' or 'school'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
