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
        Schema::create('officer_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('course_type', 20)->comment('OLC,JD,SD,NWC,NIPS,STC,LC,PRO,OTHER');
            $table->string('course_name', 150)->nullable();
            $table->date('date')->nullable();
            $table->string('grade', 20)->nullable();
            $table->string('received', 50)->nullable()->comment('Certificate received status');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_courses');
    }
};
