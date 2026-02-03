<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Falls die Tabelle schon existiert (aus dem alten Versuch), löschen wir sie sicherheitshalber
        Schema::dropIfExists('votes');

        Schema::create('votes', function (Blueprint $table) {
            $table->id('vote_id');
            $table->foreignId('survey_id')->constrained('surveys', 'survey_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
