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
        Schema::create('curriculum_vitaes', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('name');
            $table->boolean('public')->default('true');
            $table->foreignId('language_id')->index();
            $table->foreign('language_id')->on('languages')->references('id')->cascadeOnDelete();
            $table->foreignId('candidate_id')->index();
            $table->foreign('candidate_id')->on('candidates')->references('id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_vitaes');
    }
};
