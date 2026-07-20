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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->after('account_id')->constrained()->onDelete('set null');
            $table->foreignId('saving_goal_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
            $table->foreignId('recurring_transaction_id')->nullable()->after('saving_goal_id')->constrained()->onDelete('set null');
            $table->uuid('transfer_group_id')->nullable()->after('recurring_transaction_id')->index();
            $table->string('receipt_path')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
