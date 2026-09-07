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
        // 1. Drop the old QUAD columns and foreign keys from sc_dswd_assessments
        Schema::table('sc_dswd_assessments', function (Blueprint $table) {
            $table->dropForeign(['health_condition_id']);
            $table->dropForeign(['economic_status_id']);
            $table->dropForeign(['living_condition_quad_id']);
            
            $table->dropColumn([
                'age', 
                'age_points', 
                'health_condition_id', 
                'health_condition_points',
                'economic_status_id', 
                'economic_status_points', 
                'living_condition_quad_id', 
                'living_condition_points',
                'quadrant_total_points'
            ]);
        });

        // 2. Create the new pivot table for Factor Considerations (DSWD Scorings)
        Schema::create('sc_dswd_scorings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_citizen_id');
            $table->unsignedBigInteger('consideration_scoring_detail_id');
            
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('senior_citizen_id')->references('id')->on('senior_citizens')->onDelete('cascade');
            $table->foreign('consideration_scoring_detail_id', 'fk_scoring_detail')
                  ->references('id')->on('consideration_scoring_details')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sc_dswd_scorings');

        Schema::table('sc_dswd_assessments', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->decimal('age_points', 8, 2)->nullable();
            $table->unsignedBigInteger('health_condition_id')->nullable();
            $table->decimal('health_condition_points', 8, 2)->nullable();
            $table->unsignedBigInteger('economic_status_id')->nullable();
            $table->decimal('economic_status_points', 8, 2)->nullable();
            $table->unsignedBigInteger('living_condition_quad_id')->nullable();
            $table->decimal('living_condition_points', 8, 2)->nullable();
            $table->decimal('quadrant_total_points', 8, 2)->nullable();

            $table->foreign('health_condition_id')->references('id')->on('quad_parameters')->onDelete('set null');
            $table->foreign('economic_status_id')->references('id')->on('quad_parameters')->onDelete('set null');
            $table->foreign('living_condition_quad_id')->references('id')->on('quad_parameters')->onDelete('set null');
        });
    }
};
