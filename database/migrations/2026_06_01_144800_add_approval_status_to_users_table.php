<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // PENDING = awaiting admin approval
            // APPROVED = can log in
            // REJECTED = denied, cannot log in
            $table->enum('approval_status', ['PENDING', 'APPROVED', 'REJECTED'])
                  ->default('APPROVED')
                  ->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approval_status');
        });
    }
};
