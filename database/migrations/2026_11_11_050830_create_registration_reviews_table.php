<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registration_reviews', function (Blueprint $table) {
            $table->bigIncrements('review_id');
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->string('action', 30);
            $table->text('remarks')->nullable();
            $table->timestamp('reviewed_at');

            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
            $table->foreign('reviewer_id')->references('user_id')->on('users')->restrictOnDelete();
            $table->index(['registration_id', 'reviewed_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('registration_reviews'); }
};