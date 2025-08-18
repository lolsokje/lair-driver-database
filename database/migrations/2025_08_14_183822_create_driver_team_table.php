<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers');
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('series_id')->constrained('series');
            $table->unsignedSmallInteger('number');
            $table->unsignedSmallInteger('rating');
            $table->string('driver_sheet_id')->nullable();
            $table->boolean('reserve');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_team');
    }
};
