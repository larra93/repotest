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
        Schema::create('values_row', function (Blueprint $table) {
            $table->id();
    
            $table->string('col_1')->nullable();
            $table->string('col_2')->nullable();
            $table->string('col_3')->nullable();
            $table->string('col_4')->nullable();
            $table->string('col_5')->nullable();
            $table->string('col_6')->nullable();
            $table->string('col_7')->nullable();
            $table->string('col_8')->nullable();
            $table->string('col_9')->nullable();
            $table->string('col_10')->nullable();
            $table->string('col_11')->nullable();
            $table->string('col_12')->nullable();
            $table->string('col_13')->nullable();
            $table->string('col_14')->nullable();
            $table->string('col_15')->nullable();
            $table->string('col_16')->nullable();
            $table->string('col_17')->nullable();
            $table->string('col_18')->nullable();
            $table->string('col_19')->nullable();
            $table->string('col_20')->nullable();
            $table->string('col_21')->nullable();
            $table->string('col_22')->nullable();
            $table->string('col_23')->nullable();
            $table->string('col_24')->nullable();
            $table->string('col_25')->nullable();


            $table->unsignedBigInteger('daily_id');
            $table->unsignedBigInteger('daily_sheet_id');
            $table->timestamps();

            
            
            $table->foreign('daily_id')->references('id')->on('dailys')->onDelete('cascade');
            $table->foreign('daily_sheet_id')->references('id')->on('daily_sheets')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('values_row');
    }
};
