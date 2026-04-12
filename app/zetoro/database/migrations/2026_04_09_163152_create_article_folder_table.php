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
        Schema::create('article_folder', function (Blueprint $table) {
            $table->foreignUlid('article_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('folder_id')->constrained()->cascadeOnDelete();

            $table->primary(['article_id', 'folder_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_folder');
    }
};
