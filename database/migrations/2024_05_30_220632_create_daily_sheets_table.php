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
            $table->unsignedBigInteger('daily_structure_id'); 
            $table->timestamps();
            $table->foreign('daily_structure_id')->references('id')->on('daily_structure')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sheets', function (Blueprint $table) {
            $table->dropForeign(['daily_structure_id']);
        });
        Schema::dropIfExists('daily_sheets');
    }
};
