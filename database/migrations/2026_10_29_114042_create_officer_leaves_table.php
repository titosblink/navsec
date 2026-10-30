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
        Schema::create('officer_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type', 30)->comment('annual,medical,study,compassionate,maternity,paternity,other');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days')->nullable();
            $table->enum('status', ['pending','approved','rejected','completed'])->default('pending');
            $table->string('approved_by', 100)->nullable();
            $table->date('approved_date')->nullable();
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
        Schema::dropIfExists('officer_leaves');
    }
};
