<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ownership_group_id')->constrained('ownership_groups');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('series_id')->constrained('series');
            $table->string('short_name');
            $table->string('full_name');
            $table->string('team_id')->nullable();
            $table->string('primary_colour', 7);
            $table->string('secondary_colour', 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
