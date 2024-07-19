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
        Schema::create('exams_answers', function (Blueprint $table) {
            $table->id(); // Assuming this is an unsigned integer
            $table->unsignedBigInteger('attempt_id'); // Change to match the data type of exams_attempt.id
            $table->foreign('attempt_id')->references('id')->on('exams_attempt')->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('answer_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_anwsers');
    }
};
