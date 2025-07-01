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
        Schema::create('space_missions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('destination');
            $table->text('description');
            $table->date('launch_date');
            $table->integer('duration_days');
            $table->enum('status', ['planned', 'active', 'completed', 'failed', 'cancelled']);
            $table->string('agency');
            $table->integer('crew_size');
            $table->enum('mission_type', ['exploration', 'research', 'colonization', 'mining', 'rescue', 'maintenance']);
            $table->decimal('budget_millions', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('space_missions');
    }
};
