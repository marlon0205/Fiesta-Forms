<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('is_active');
            $table->index('created_at');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->index('survey_id');
        });

        Schema::table('answer_options', function (Blueprint $table) {
            $table->index('question_id');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->index('survey_id');
            $table->index('user_id');
            $table->unique(['survey_id', 'user_id'], 'votes_survey_user_unique');
        });

        Schema::table('vote_answers', function (Blueprint $table) {
            $table->index('vote_id');
            $table->index('question_id');
            $table->index('option_id');
        });

        Schema::table('service__categories', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('product__categories', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_survey_user_unique');
            $table->dropIndex(['survey_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('vote_answers', function (Blueprint $table) {
            $table->dropIndex(['vote_id']);
            $table->dropIndex(['question_id']);
            $table->dropIndex(['option_id']);
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['survey_id']);
        });

        Schema::table('answer_options', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
        });

        Schema::table('service__categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('product__categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};
