<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dependents', function (Blueprint $table) {
            $table->bigIncrements('dependent_id');
            $table->unsignedBigInteger('registration_id');
            $table->string('full_name', 200);
            $table->string('relationship', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('remarks')->nullable();
            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('dependents'); }
};