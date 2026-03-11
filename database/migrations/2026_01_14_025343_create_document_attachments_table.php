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
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to documents table
            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->onDelete('cascade'); // delete attachments if document is deleted
            
            $table->string('file_path'); // path in storage
            $table->string('file_name'); // original filename
            $table->string('file_type', 50); // file extension or MIME type
            $table->unsignedBigInteger('file_size'); // size in bytes
            
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_attachments');
    }
};