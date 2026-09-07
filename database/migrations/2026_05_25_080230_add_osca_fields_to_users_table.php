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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->unsignedBigInteger('office_id')->nullable()->after('username');
            $table->unsignedBigInteger('access_level_id')->nullable()->after('office_id');
            $table->boolean('is_active')->default(true)->after('access_level_id');
            $table->unsignedBigInteger('created_by')->nullable()->after('is_active');
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            $table->foreign('office_id')->references('id')->on('offices')->nullOnDelete();
            $table->foreign('access_level_id')->references('id')->on('access_levels')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['access_level_id']);
            $table->dropColumn(['username', 'office_id', 'access_level_id', 'is_active', 'created_by', 'updated_by']);
        });
    }
};
