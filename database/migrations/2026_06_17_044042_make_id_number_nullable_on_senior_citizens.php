<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make id_number nullable so auto-numbering can insert null
     * then immediately update within a DB transaction (M-XXXX format).
     */
    public function up(): void
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            // Must drop the existing unique index before redefining as nullable
            $table->dropUnique('senior_citizens_id_number_unique');
            $table->string('id_number')->nullable()->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('senior_citizens', function (Blueprint $table) {
            $table->string('id_number')->nullable(false)->change();
        });
    }
};
