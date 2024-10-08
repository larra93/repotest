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
            $table->unsignedBigInteger('daily_sheet_id'); //quizas podemos sacar este ya que si tenemos el daily_id ya tenemos tambein el daily_sheet_id
            $table->integer('row'); 
            $table->unsignedBigInteger('daily_id');
            $table->timestamps();

            
            
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
