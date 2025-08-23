<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('min_age');
            $table->unsignedSmallInteger('max_age');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_ranges');
    }
};
