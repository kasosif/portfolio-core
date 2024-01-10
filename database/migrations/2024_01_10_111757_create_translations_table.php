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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('translatable_id');
            $table->string('translatable_type');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('job_description')->nullable();
            $table->string('about')->nullable();
            $table->string('address')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('testimony')->nullable();
            $table->string('testimony_name')->nullable();
            $table->string('testimony_job_description')->nullable();
            $table->string('testimony_country')->nullable();
            $table->string('degree')->nullable();
            $table->string('institute')->nullable();
            $table->string('institute_country')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_country')->nullable();
            $table->string('issuer')->nullable();
            $table->string('name')->nullable();

            $table->foreignId('language_id')->index();
            $table->foreign('language_id')->on('languages')->references('id')->cascadeOnDelete();
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
        Schema::dropIfExists('translations');
    }
};
