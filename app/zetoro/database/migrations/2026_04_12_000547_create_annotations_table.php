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
            $table->foreignUlid('article_id')->constrained()->cascadeOnDelete();
            $table->float('x_min');
            $table->float('y_min');
            $table->float('x_max');
            $table->float('y_max');
            $table->string('highlight_color')->nullable(); // if null -> no highlight
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
