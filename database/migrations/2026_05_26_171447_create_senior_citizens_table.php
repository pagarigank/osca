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
        Schema::create('senior_citizens', function (Blueprint $table) {
            $table->id();

            // --- Identification ---
            $table->string('id_number')->unique()->comment('Generated OSCA ID Number');
            $table->string('ncsc_rrn')->nullable()->comment('NCSC Reference Registration Number');
            $table->string('national_id_no')->nullable()->comment('PhilSys National ID No.');
            $table->string('voter_id_no')->nullable()->comment("Voter's ID Number");
            $table->string('precinct_no')->nullable();
            $table->string('precinct_place')->nullable();
            $table->string('new_id_number')->nullable()->comment('Replacement ID Number');

            // --- Name ---
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable()->comment('Jr., Sr., III, etc.');

            // --- Personal Details ---
            $table->date('date_of_birth');
            $table->string('country_of_birth')->nullable();
            $table->unsignedBigInteger('civil_status_id')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->string('citizenship')->nullable()->comment('e.g. Filipino, Dual Citizen');
            $table->string('dual_citizen_document')->nullable();
            $table->string('blood_type')->nullable()->comment('e.g. A+, B-, O+');
            $table->string('affiliation')->nullable()->comment('Political/Organizational Affiliation');

            // --- Contact ---
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();
            $table->string('messenger_account')->nullable()->comment('Facebook/Messenger');

            // --- Address ---
            $table->string('house_no')->nullable();
            $table->string('street')->nullable();
            $table->string('purok')->nullable();
            $table->string('purok_name')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('province')->nullable();

            // --- Living Situation ---
            $table->unsignedBigInteger('living_condition_id')->nullable();
            $table->unsignedBigInteger('place_of_living_id')->nullable();

            // --- Emergency Contact ---
            $table->string('emergency_contact')->nullable()->comment('Emergency Contact Person Name');
            $table->string('emergency_contact_no')->nullable()->comment('Emergency Contact Number');

            // --- Transfer to Other LGU ---
            $table->string('transferred_to_lgu')->nullable();
            $table->string('transferred_first_name')->nullable();
            $table->string('transferred_suffix')->nullable();
            $table->string('transferred_relation')->nullable();

            // --- NCSC Tab Fields ---
            $table->date('ncsc_validation_date')->nullable();
            $table->string('sc_category')->nullable()->comment('e.g. Centenarian, PWD, Solo Parent Senior');
            $table->text('ncsc_remarks')->nullable();

            // --- CSWDO Tab Fields ---
            $table->date('cswdo_assessment_date')->nullable();
            $table->decimal('cswdo_total_score', 8, 2)->nullable();
            $table->text('cswdo_remarks')->nullable();

            // --- Status ---
            $table->date('effective_date')->nullable();
            $table->string('status')->default('ACTIVE')->comment('ACTIVE or INACTIVE');
            $table->text('inactive_reason')->nullable();
            $table->boolean('is_active')->default(true);

            // --- Audit ---
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // --- Foreign Keys ---
            $table->foreign('civil_status_id')->references('id')->on('civil_statuses')->onDelete('set null');
            $table->foreign('gender_id')->references('id')->on('genders')->onDelete('set null');
            $table->foreign('nationality_id')->references('id')->on('nationalities')->onDelete('set null');
            $table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('set null');
            $table->foreign('living_condition_id')->references('id')->on('living_conditions')->onDelete('set null');
            $table->foreign('place_of_living_id')->references('id')->on('place_of_livings')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('senior_citizens');
    }
};
