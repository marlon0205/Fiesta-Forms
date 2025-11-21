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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id('survey_id');
            $table->string('title');
            $table->string('description');
            $table->timestamp('survey_created_at');
            $table->integer('duration_days');
            $table->boolean('is_active');

            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->foreignId('service_category_id')->constrained('service__categories', 'service_category_id');
            $table->foreignId('product_category_id')->constrained('product__categories', 'product_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
