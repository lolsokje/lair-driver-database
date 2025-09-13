<?php

declare(strict_types=1);

use App\Enums\DevelopmentRoundStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained('seasons');
            $table->unsignedSmallInteger('status')->default(DevelopmentRoundStatus::STARTED);
            $table->text('error')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_rounds');
    }
};
