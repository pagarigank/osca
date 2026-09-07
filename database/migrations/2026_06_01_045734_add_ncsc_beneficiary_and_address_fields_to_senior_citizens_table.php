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
        Schema::table('senior_citizens', function (Blueprint $table) {
            // NCSC Address Abroad
            $table->string('ncsc_address_abroad_house_no')->nullable()->after('ncsc_remarks');
            $table->string('ncsc_address_abroad_street')->nullable()->after('ncsc_address_abroad_house_no');
            $table->string('ncsc_address_abroad_county')->nullable()->after('ncsc_address_abroad_street');
            $table->string('ncsc_address_abroad_state')->nullable()->after('ncsc_address_abroad_county');
            $table->string('ncsc_address_abroad_country')->nullable()->after('ncsc_address_abroad_state');
            $table->string('ncsc_address_abroad_zip')->nullable()->after('ncsc_address_abroad_country');

            // NCSC Beneficiaries
            $table->string('ncsc_primary_beneficiary_name')->nullable()->after('ncsc_address_abroad_zip');
            $table->string('ncsc_primary_beneficiary_rel')->nullable()->after('ncsc_primary_beneficiary_name');
            $table->string('ncsc_secondary_beneficiary_name')->nullable()->after('ncsc_primary_beneficiary_rel');
            $table->string('ncsc_secondary_beneficiary_rel')->nullable()->after('ncsc_secondary_beneficiary_name');
        });
    }

    public function down(): void
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->dropColumn([
                'ncsc_address_abroad_house_no',
                'ncsc_address_abroad_street',
                'ncsc_address_abroad_county',
                'ncsc_address_abroad_state',
                'ncsc_address_abroad_country',
                'ncsc_address_abroad_zip',
                'ncsc_primary_beneficiary_name',
                'ncsc_primary_beneficiary_rel',
                'ncsc_secondary_beneficiary_name',
                'ncsc_secondary_beneficiary_rel'
            ]);
        });
    }
};
