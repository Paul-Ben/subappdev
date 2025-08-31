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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Monthly, Yearly
            $table->string('slug')->unique(); // free, monthly, yearly
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0); // Price in Naira
            $table->enum('billing_cycle', ['free', 'monthly', 'yearly']); 
            $table->integer('meeting_duration_limit')->nullable(); // in minutes, null for unlimited
            $table->integer('max_participants')->default(20);
            $table->integer('storage_limit')->nullable(); // in GB, null for unlimited
            $table->boolean('has_recording')->default(false);
            $table->boolean('has_breakout_rooms')->default(false);
            $table->boolean('has_admin_tools')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
