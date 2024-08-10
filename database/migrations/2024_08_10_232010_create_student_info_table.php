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
        Schema::create('student_info', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('registration_no')->unique();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('blood_group');
            $table->string('nationality');
            $table->string('city');
            $table->string('passport_photo')->nullable();
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->string('section');
            $table->string('parent_first_name');
            $table->string('parent_last_name');
            $table->string('parent_phone');
            $table->string('parent_email');
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('school_id'); 
            $table->foreign('school_id')->references('id')->on('schools');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_info');
    }
};
