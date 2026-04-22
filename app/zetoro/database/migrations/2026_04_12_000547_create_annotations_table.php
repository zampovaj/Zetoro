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
        Schema::create('annotations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('file_id')->constrained()->cascadeOnDelete();
            $table->jsonb('rectangles'); // array of rectangles
            $table->integer('page');
            $table->string('highlight_color')->default('#FFFF0080');
            $table->string('note')->nullable(); // if null -> no note
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annotations');
    }
};
