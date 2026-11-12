<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->bigIncrements('registration_id');
            $table->unsignedBigInteger('user_id');
            $table->string('registration_no', 50)->unique();
            $table->string('family_name', 100);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('gender', 30)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('marital_status', 50)->nullable();
            $table->string('citizenship', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('mobile_no', 30)->nullable();
            $table->string('landline', 30)->nullable();
            $table->string('tin_no', 50)->nullable();
            $table->string('sss_gsis_no', 50)->nullable();
            $table->string('email')->nullable();
            $table->unsignedSmallInteger('year_graduated')->nullable();
            $table->string('graduation_school')->nullable();
            $table->string('status', 30)->default('Draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by')->references('user_id')->on('users')->nullOnDelete();
            $table->index(['status', 'submitted_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('registrations'); }
};