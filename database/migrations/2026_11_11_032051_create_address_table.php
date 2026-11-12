<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('address_id');
            $table->unsignedBigInteger('registration_id');
            $table->string('address_type', 50);
            $table->string('address_line', 255)->nullable();
            $table->string('house_no', 50)->nullable();
            $table->string('street', 150)->nullable();
            $table->string('barangay', 150)->nullable();
            $table->string('municipality', 150)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('province', 150)->nullable();
            $table->string('region', 150)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
            $table->index(['registration_id', 'address_type']);
        });
    }

    public function down(): void { Schema::dropIfExists('addresses'); }
};