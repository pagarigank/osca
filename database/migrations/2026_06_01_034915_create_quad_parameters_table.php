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
        Schema::create('quad_parameters', function (Blueprint $table) {
            $table->id();
            $table->enum('quad_type', ['QUAD1', 'QUAD2', 'QUAD3', 'QUAD4'])->comment('QUAD1=Age, QUAD2=Health, QUAD3=Economic, QUAD4=Living Condition');
            $table->string('name')->comment('Description/label for this parameter');
            $table->decimal('points', 8, 2)->default(0)->comment('Points awarded for this parameter');
            $table->integer('age_from')->nullable()->comment('QUAD1 only: age range start');
            $table->integer('age_to')->nullable()->comment('QUAD1 only: age range end');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quad_parameters');
    }
};
