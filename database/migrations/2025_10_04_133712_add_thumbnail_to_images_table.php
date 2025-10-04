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
        Schema::table('images', function (Blueprint $table) {
            // Safe: removes dependency on a non-existent column
            if (!Schema::hasColumn('images', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('dzi_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            if (Schema::hasColumn('images', 'thumbnail_path')) {
                $table->dropColumn('thumbnail_path');
            }
        });
    }
};
