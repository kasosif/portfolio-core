<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_language', function (Blueprint $table) {
            $table->foreignId('candidate_id')->index();
            $table->foreign('candidate_id')->on('candidates')->references('id')->cascadeOnDelete();
            $table->foreignId('language_id')->index();
            $table->foreign('language_id')->on('languages')->references('id')->cascadeOnDelete();
            $table->primary(['candidate_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_language');
    }
};
