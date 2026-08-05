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
        Schema::create('deposit_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2)->default(25);
            $table->string('currency', 3)->default('USD');
            $table->json('payment_methods')->nullable();
            $table->json('offsite_payment_methods')->nullable();
            $table->json('eyebrow')->nullable();
            $table->json('title')->nullable();
            $table->json('text')->nullable();
            $table->json('note')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_settings');
    }
};
