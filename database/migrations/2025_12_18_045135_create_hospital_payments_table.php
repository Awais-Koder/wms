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
        Schema::create('hospital_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collected_by_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('month'); // Format: YYYY-MM
            $table->date('collection_date')->nullable();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_collected')->default(false);
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Ensure unique payment per hospital per month
            $table->unique(['hospital_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_payments');
    }
};
