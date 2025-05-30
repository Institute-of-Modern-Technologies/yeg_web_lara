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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Standard fee amount
            $table->decimal('partner_discount', 10, 2)->default(100.00); // Default discount for partner schools is 100 GHC
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};
