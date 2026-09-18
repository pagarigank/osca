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
        Schema::create('payout_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payout_cutoff_id')->constrained('payout_cutoffs')->onDelete('cascade');
            $table->foreignId('senior_citizen_funding_id')->constrained('senior_citizen_fundings')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('received_date')->nullable();
            $table->string('proof_path')->nullable();
            $table->boolean('is_received')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_transactions');
    }
};
