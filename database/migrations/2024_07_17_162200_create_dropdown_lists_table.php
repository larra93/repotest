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
        Schema::create('dropdown_lists', function (Blueprint $table) {

            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('field_id'); 
            $table->timestamps();
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropdown_lists');
    }
};
