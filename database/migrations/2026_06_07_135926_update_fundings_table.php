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
        Schema::table('fundings', function (Blueprint $table) {
            $table->renameColumn('name', 'fund_source');
        });

        Schema::table('fundings', function (Blueprint $table) {
            $table->string('provider')->after('id')->nullable();
            $table->decimal('amount', 15, 2)->after('fund_source')->default(0);
            $table->date('effective_date')->after('amount')->nullable();
            $table->integer('beneficiary')->after('effective_date')->default(0);
            $table->decimal('total', 15, 2)->after('beneficiary')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fundings', function (Blueprint $table) {
            $table->dropColumn(['provider', 'amount', 'effective_date', 'beneficiary', 'total']);
        });

        Schema::table('fundings', function (Blueprint $table) {
            $table->renameColumn('fund_source', 'name');
        });
    }
};
