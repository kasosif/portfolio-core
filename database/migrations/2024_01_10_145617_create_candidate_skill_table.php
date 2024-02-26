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
        Schema::create('candidate_skill', function (Blueprint $table) {
            $table->foreignId('candidate_id')->index();
            $table->foreign('candidate_id')->on('candidates')->references('id')->cascadeOnDelete();
            $table->foreignId('skill_id')->index();
            $table->foreign('skill_id')->on('skills')->references('id')->cascadeOnDelete();
            $table->integer('percentage')->nullable();
            $table->boolean('icon_only')->default(false);
            $table->primary(['candidate_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_skill');
    }
};
