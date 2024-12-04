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
        // class list table
        Schema::create('class_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();
        });

        // combine exam, assignment and class into a single entity
        Schema::dropIfExists('class_details');
        Schema::dropIfExists('assignment_details');
        Schema::dropIfExists('exam_details');

        Schema::create('information_details', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['exam', 'assignment', 'class']);
            $table->enum('assignment_type', ['lab', 'individual', 'group_project'])->nullable();
            $table->enum('exam_type', ['quiz', 'midterm', 'final'])->nullable();
            $table->string('venue')->nullable();
            $table->date('scheduled_at');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
