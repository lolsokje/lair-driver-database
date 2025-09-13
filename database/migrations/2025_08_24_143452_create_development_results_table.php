<?php

declare(strict_types=1);

use App\Enums\DevelopmentResultStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_round_id')->constrained('development_rounds');
            $table->foreignId('series_id')->nullable()->constrained('series');
            $table->foreignId('team_id')->nullable()->constrained('teams');
            $table->foreignId('driver_id')->constrained('drivers');
            $table->unsignedSmallInteger('old_rating');
            $table->smallInteger('development');
            $table->unsignedSmallInteger('status')->default(DevelopmentResultStatus::PENDING);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_results');
    }
};
