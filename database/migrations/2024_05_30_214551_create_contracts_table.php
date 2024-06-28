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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('name_contract');
            $table->string('NSAP');
            $table->string('DEN');
            $table->string('project');
            $table->string('API');
            $table->string('CC');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_revisor_pyc_required')->default(false);
            $table->boolean('is_revisor_cc_required')->default(false);
            $table->boolean('is_revisor_other_area_required')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['id_company']);
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('contracts');
    }
};
