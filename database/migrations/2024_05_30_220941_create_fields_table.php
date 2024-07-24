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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable(); 
            $table->string('field_type');
            $table->integer('step')->nullable();
            $table->string('required');
            $table->unsignedBigInteger('daily_sheet_id'); 
            $table->timestamps();

            $table->foreign('daily_sheet_id')->references('id')->on('daily_sheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropForeign(['daily_sheet_id']);
        });
        Schema::dropIfExists('fields');
    }
};
