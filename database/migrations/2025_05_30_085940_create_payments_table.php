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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('final_amount', 10, 2);
            $table->string('reference_number')->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('payment_method')->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
