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
        Schema::create('officer_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('appointment', 150);
            $table->string('appt_code', 30)->nullable();
            $table->string('ship', 100)->nullable();
            $table->string('ship_code', 20)->nullable();
            $table->string('command', 100)->nullable();
            $table->string('cmd_code', 20)->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('type', 30)->default('posting')->comment('posting, transfer, secondment');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_appointments');
    }
};
