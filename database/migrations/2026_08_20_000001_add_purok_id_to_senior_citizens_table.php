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
            // Link a Senior Citizen to a Purok from the Purok maintenance CRUD.
            // Nullable + set null so deleting/deactivating a purok does not orphan records.
            $table->unsignedBigInteger('purok_id')->nullable()->after('barangay_id');
            $table->foreign('purok_id')->references('id')->on('puroks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->dropForeign(['purok_id']);
            $table->dropColumn('purok_id');
        });
    }
};
