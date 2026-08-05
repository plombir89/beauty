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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
            $table->string('key')->unique();
            $table->json('slug');
            $table->json('title');
            $table->json('display_price');
            $table->decimal('price_from', 10, 2)->nullable()->index();
            $table->json('duration')->nullable();
            $table->json('summary');
            $table->json('benefits')->nullable();
            $table->json('details')->nullable();
            $table->json('skin_type')->nullable();
            $table->json('note')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedSmallInteger('featured_sort_order')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
