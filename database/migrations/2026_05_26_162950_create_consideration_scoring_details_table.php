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
        Schema::create('consideration_scoring_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factor_consideration_id')->comment('Foreign Key to Factor for Consideration');
            $table->string('particular')->comment('Particular');
            $table->integer('guide_points')->comment('Guide Points');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('factor_consideration_id')->references('id')->on('factor_considerations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consideration_scoring_details');
    }
};
