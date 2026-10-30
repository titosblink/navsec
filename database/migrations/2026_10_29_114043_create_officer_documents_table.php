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
        Schema::create('officer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('officer_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('document_type', 50)->comment('commission_cert,academic_cert,posting_letter,medical_report,id_document,other');
            $table->string('file_path', 255);
            $table->string('original_name', 255)->nullable();
            $table->unsignedInteger('file_size')->nullable()->comment('bytes');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('officer_documents');
    }
};
