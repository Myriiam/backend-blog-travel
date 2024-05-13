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
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->startingValue(1);
            $table->string('name', 40);
        });

        Schema::create('article_category', function (Blueprint $table) {
          /*   $table->foreign('article_id')->references('id')->on('article')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('category')
            ->onDelete('cascade')->onUpdate('cascade'); */
            $table->id()->startingValue(1);
            $table->foreignId('article_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_category');
        Schema::dropIfExists('categories');
    }
};
