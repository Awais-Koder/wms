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
        Schema::disableForeignKeyConstraints();

        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('uuid', 255);
            $table->foreignId('country_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tehsil_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('address');
            $table->string('doctor_name', 100);
            $table->string('cnic', 20);
            $table->decimal('amount', 8, 2);
            $table->integer('agreement_duration');
            $table->date('date');
            $table->string('phc_number', 150);
            $table->string('mobile_number_1', 20);
            $table->string('mobile_number_2', 20);
            $table->string('agreement_image', 150);
            $table->foreignId('province_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
