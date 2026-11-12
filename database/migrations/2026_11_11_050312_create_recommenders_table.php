<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recommenders', function (Blueprint $table) {
            $table->bigIncrements('recommender_id');
            $table->unsignedBigInteger('registration_id')->unique();
            $table->string('last_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->unsignedSmallInteger('year_graduated')->nullable();
            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
        });
    }

    public function down(): void { Schema::dropIfExists('recommenders'); }
};