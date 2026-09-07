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
        // --- Authorized Representatives (max 3 per SC) ---
        Schema::create('sc_authorized_representatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('civil_status_id')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('civil_status_id')->references('id')->on('civil_statuses')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Caregivers ---
        Schema::create('sc_caregivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('relationship')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Pre-existing Conditions (max 5, linked to diagnoses) ---
        Schema::create('sc_pre_existing_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('diagnosis_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('diagnosis_id')->references('id')->on('diagnoses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Disability Diagnoses (max 5, linked to diagnoses) ---
        Schema::create('sc_disability_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('diagnosis_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('diagnosis_id')->references('id')->on('diagnoses')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Frailties (max 3) ---
        Schema::create('sc_frailties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('frailty_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('frailty_id')->references('id')->on('frailties')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Pensions (max 2) ---
        Schema::create('sc_pensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('pension_source_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('pension_source_id')->references('id')->on('pension_sources')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Regular Incomes (max 2) ---
        Schema::create('sc_regular_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('regular_income_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('regular_income_id')->references('id')->on('regular_incomes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- Other Incomes (max 2) ---
        Schema::create('sc_other_incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('other_income_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('other_income_id')->references('id')->on('other_incomes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // --- DSWD Assessment (1 per SC) ---
        Schema::create('sc_dswd_assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id')->unique();
            $table->boolean('ambulatory')->nullable()->comment('YES=true / NO=false');
            $table->unsignedBigInteger('place_of_living_id')->nullable();
            $table->unsignedBigInteger('living_condition_id')->nullable();
            // Quadrant
            $table->integer('age')->nullable()->comment('Computed: current year - birth year');
            $table->decimal('age_points', 8, 2)->nullable()->comment('Auto-filled from QUAD1');
            $table->unsignedBigInteger('health_condition_id')->nullable()->comment('FK to quad_parameters QUAD2');
            $table->decimal('health_condition_points', 8, 2)->nullable();
            $table->unsignedBigInteger('economic_status_id')->nullable()->comment('FK to quad_parameters QUAD3');
            $table->decimal('economic_status_points', 8, 2)->nullable();
            $table->unsignedBigInteger('living_condition_quad_id')->nullable()->comment('FK to quad_parameters QUAD4');
            $table->decimal('living_condition_points', 8, 2)->nullable();
            $table->decimal('quadrant_total_points', 8, 2)->nullable()->comment('Sum of all 4 quad points');
            // Assessment
            $table->text('assessment')->nullable();
            $table->enum('recommendation', ['Eligible', 'Not Eligible'])->nullable();
            // Encoded / Validated By
            $table->unsignedBigInteger('encoded_by')->nullable();
            $table->timestamp('encoded_at')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            // Audit
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('place_of_living_id')->references('id')->on('place_of_livings')->onDelete('set null');
            $table->foreign('living_condition_id')->references('id')->on('living_conditions')->onDelete('set null');
            $table->foreign('health_condition_id')->references('id')->on('quad_parameters')->onDelete('set null');
            $table->foreign('economic_status_id')->references('id')->on('quad_parameters')->onDelete('set null');
            $table->foreign('living_condition_quad_id')->references('id')->on('quad_parameters')->onDelete('set null');
            $table->foreign('encoded_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_dswd_assessments');
        Schema::dropIfExists('sc_other_incomes');
        Schema::dropIfExists('sc_regular_incomes');
        Schema::dropIfExists('sc_pensions');
        Schema::dropIfExists('sc_frailties');
        Schema::dropIfExists('sc_disability_diagnoses');
        Schema::dropIfExists('sc_pre_existing_conditions');
        Schema::dropIfExists('sc_caregivers');
        Schema::dropIfExists('sc_authorized_representatives');
    }
};
