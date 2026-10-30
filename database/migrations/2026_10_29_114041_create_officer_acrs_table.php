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
        Schema::create('officer_acrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->year('report_year');
            $table->string('reporting_officer', 100)->nullable();
            $table->string('countersigning_officer', 100)->nullable();
            $table->tinyInteger('overall_rating')->nullable()->comment('1-9 scale');
            $table->string('fitness_category', 10)->nullable()->comment('A1,A2,B1,B2,C,D');
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('remarks')->nullable();
            $table->date('date_submitted')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_acrs');
    }
};
