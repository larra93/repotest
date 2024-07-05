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
        Schema::create('daily_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('step')->nullable();
            $table->unsignedBigInteger('contract_id'); 
            $table->timestamps();

            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sheets', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
        Schema::dropIfExists('daily_sheets');
    }
};
