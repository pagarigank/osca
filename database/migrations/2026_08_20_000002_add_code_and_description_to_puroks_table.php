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
        Schema::table('puroks', function (Blueprint $table) {
            // Purok Code: short unique code within a barangay (e.g. PRK-01)
            $table->string('purok_code')->nullable()->after('name');
            // Purok Description: free-text notes about the purok
            $table->text('description')->nullable()->after('purok_code');
        });

        // A purok code must be unique within a barangay (mirrors the name rule).
        // Added as a raw index so it is re-runnable on servers where the
        // column may already exist.
        Schema::table('puroks', function (Blueprint $table) {
            $table->unique(['barangay_id', 'purok_code'], 'puroks_barangay_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('puroks', function (Blueprint $table) {
            $table->dropUnique('puroks_barangay_code_unique');
            $table->dropColumn(['purok_code', 'description']);
        });
    }
};
