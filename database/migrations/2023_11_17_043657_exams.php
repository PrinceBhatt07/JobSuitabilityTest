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
        Schema::create('exams', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('exam_name');
            $table->string('date');
            $table->time('from_time');
            $table->time('to_time');
            $table->time('time')->format('H:i:s');
            $table->float('marks')->default(0);
            $table->float('passing_marks')->default(0);
            $table->string('enterance_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
