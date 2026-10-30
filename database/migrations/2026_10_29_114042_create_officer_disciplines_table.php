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
        Schema::create('officer_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('case_no', 50)->nullable();
            $table->string('charge', 255);
            $table->date('date_of_offence')->nullable();
            $table->date('date_of_trial')->nullable();
            $table->enum('severity', ['minor','major'])->default('minor');
            $table->string('outcome', 100)->nullable();
            $table->string('punishment', 255)->nullable();
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
        Schema::dropIfExists('officer_disciplines');
    }
};
