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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_reference')->unique(); // Unique payment reference
            $table->string('gateway'); // credo, stripe
            $table->string('gateway_transaction_id')->nullable(); // Gateway's transaction ID
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('NGN');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('type', ['subscription', 'upgrade', 'renewal', 'refund'])->default('subscription');
            $table->text('description')->nullable();
            $table->json('gateway_response')->nullable(); // Store full gateway response
            $table->datetime('paid_at')->nullable();
            $table->datetime('failed_at')->nullable();
            $table->string('failure_reason')->nullable();
            $table->json('metadata')->nullable(); // Additional payment data
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'status']);
            $table->index(['gateway', 'status']);
            $table->index('payment_reference');
            $table->index('gateway_transaction_id');
            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
