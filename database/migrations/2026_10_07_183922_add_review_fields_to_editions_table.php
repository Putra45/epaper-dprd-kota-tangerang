<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->integer('flipbook_width')->default(540)->after('status');
            $table->integer('flipbook_height')->default(730)->after('flipbook_width');
            $table->boolean('is_spread')->default(true)->after('flipbook_height');
            $table->json('excluded_pages')->nullable()->after('is_spread');
        });
    }

    public function down(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropColumn(['flipbook_width', 'flipbook_height', 'is_spread', 'excluded_pages']);
        });
    }
};
