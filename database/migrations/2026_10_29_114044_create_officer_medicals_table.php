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
        Schema::create('officer_medicals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->date('exam_date');
            $table->string('medical_category', 10)->nullable()->comment('A1,A2,B1,B2,C,D');
            $table->enum('fitness_status', ['fit','unfit','restricted'])->default('fit');
            $table->string('examining_officer', 100)->nullable();
            $table->string('hospital_facility', 150)->nullable();
            $table->text('findings')->nullable();
            $table->date('next_exam_date')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_medicals');
    }
};
