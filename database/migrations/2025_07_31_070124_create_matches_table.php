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
    Schema::create('matches', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('country_id'); 
        $table->string('opponent');
        $table->date('match_date');
        $table->unsignedInteger('country_score')->nullable();
        $table->unsignedInteger('opponent_score')->nullable();
        $table->timestamps();
        $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
