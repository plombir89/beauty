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
        Schema::create('home_hero_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('eyebrow')->nullable();
            $table->json('title');
            $table->json('lead')->nullable();
            $table->json('text')->nullable();
            $table->json('primary_label')->nullable();
            $table->json('secondary_label')->nullable();
            $table->json('stats')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_hero_blocks');
    }
};
