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
        Schema::create('officer_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('qual_type', 30)->comment('degree,diploma,certificate,professional,trade');
            $table->string('title', 150);
            $table->string('institution', 150)->nullable();
            $table->string('country', 60)->nullable()->default('Nigeria');
            $table->year('year_obtained')->nullable();
            $table->string('grade_class', 50)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_qualifications');
    }
};
