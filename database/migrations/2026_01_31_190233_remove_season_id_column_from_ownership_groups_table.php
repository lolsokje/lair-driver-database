<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ownership_groups', function (Blueprint $table) {
            $table->dropForeign(['season_id']);
            $table->dropColumn('season_id');
        });
    }

    public function down(): void
    {
        Schema::table('ownership_groups', function (Blueprint $table) {
            $table->foreignId('season_id')->constrained('seasons');
        });
    }
};
