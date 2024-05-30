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
            $table->string('n_sap');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->unsignedBigInteger('id_company');
            $table->timestamps();

            $table->foreign('id_company')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::dropIfExists('contracts');
    }
};
