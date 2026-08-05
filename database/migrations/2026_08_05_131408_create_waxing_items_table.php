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
        Schema::create('waxing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waxing_group_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->json('title');
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->json('display_price')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['waxing_group_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waxing_items');
    }
};
