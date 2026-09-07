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
        Schema::create('senior_citizen_fundings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');

            // Pension
            $table->unsignedBigInteger('pension_source_id')->nullable();
            $table->decimal('monthly_pension', 10, 2)->nullable();

            // Funding & Payroll
            $table->unsignedBigInteger('funding_id')->nullable();
            $table->unsignedBigInteger('payroll_provider_id')->nullable();

            // Regular Income
            $table->string('regular_income_type')->nullable();
            $table->decimal('regular_income_amount', 10, 2)->nullable();

            // Other Income
            $table->unsignedBigInteger('other_income_id')->nullable();
            $table->decimal('other_income_amount', 10, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('pension_source_id')->references('id')->on('pension_sources')->onDelete('set null');
            $table->foreign('funding_id')->references('id')->on('fundings')->onDelete('set null');
            $table->foreign('payroll_provider_id')->references('id')->on('payroll_providers')->onDelete('set null');
            $table->foreign('other_income_id')->references('id')->on('other_incomes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_citizen_fundings');
    }
};
