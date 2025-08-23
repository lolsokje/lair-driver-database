<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dev_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('age_range_id')->constrained('age_ranges');
            $table->unsignedSmallInteger('min_rating');
            $table->unsignedSmallInteger('max_rating');
            $table->smallInteger('min_dev');
            $table->smallInteger('max_dev');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dev_ranges');
    }
};
