<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->bigIncrements('document_id');
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('document_type_id');
            $table->string('file_name', 255);
            $table->string('storage_path', 500);
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('uploaded_at');
            $table->string('verification_status', 30)->default('Pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('remarks')->nullable();

            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
            $table->foreign('document_type_id')->references('document_type_id')->on('document_types')->restrictOnDelete();
            $table->foreign('uploaded_by')->references('user_id')->on('users')->restrictOnDelete();
            $table->foreign('verified_by')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('registration_documents'); }
};