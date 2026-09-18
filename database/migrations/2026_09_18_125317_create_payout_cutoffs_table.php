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
        Schema::create('payout_cutoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funding_id')->constrained('fundings')->onDelete('cascade');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('status')->default('OPEN'); // OPEN, FREEZE, CLOSE
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
        Schema::dropIfExists('payout_cutoffs');
    }
};
