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
        Schema::create('values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id'); 
            $table->string('value'); 
            $table->unsignedBigInteger('daily_sheet_id'); 
            $table->integer('row'); 
            $table->timestamps();
            $table->unsignedBigInteger('daily_id');
            
            
            $table->foreign('daily_id')->references('id')->on('dailys')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->foreign('daily_sheet_id')->references('id')->on('daily_sheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values');
    }
};
