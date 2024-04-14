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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('reg_number');
            $table->enum('semester', ['first', 'second']);
            $table->unsignedBigInteger('course_id');
            $table->integer('score');
            $table->char('grade', 1);
            $table->integer('year');
            $table->integer('credit_load');
            $table->timestamps();

            $table->foreign('reg_number')->references('reg_number')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
