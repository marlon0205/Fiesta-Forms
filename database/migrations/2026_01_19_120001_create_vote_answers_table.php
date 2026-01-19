<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_answers', function (Blueprint $table) {
            $table->id('vote_answer_id');
            $table->foreignId('vote_id')->constrained('votes', 'vote_id')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions', 'question_id')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('answer_options', 'option_id')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_answers');
    }
};
