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
        Schema::create('votes', function (Blueprint $table) {
            $table->id('vote_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('question_id')->constrained('questions', 'question_id');
            $table->foreignId('option_id')->constrained('answer_options', 'option_id');
            $table->timestamp('voted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
