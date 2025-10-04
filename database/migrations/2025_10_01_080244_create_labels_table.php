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
       Schema::create('labels', function (Blueprint $table) {
    $table->id();
    $table->foreignId('image_id')->constrained()->cascadeOnDelete();
    $table->string('feature_name');
    $table->decimal('nx', 8, 5);
    $table->decimal('ny', 8, 5);
    $table->text('description')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
