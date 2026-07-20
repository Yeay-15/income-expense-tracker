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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table (cascade means if user is deleted, their transactions are deleted too)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Transaction details
            $table->enum('type', ['income', 'expense']);
            $table->decimal('amount', 15, 2); // 15 digits total, 2 decimal places for accuracy
            $table->string('description');
            $table->date('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
