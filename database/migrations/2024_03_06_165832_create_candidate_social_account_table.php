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
        Schema::create('candidate_social_account', function (Blueprint $table) {
            $table->foreignId('candidate_id')->index();
            $table->foreign('candidate_id')->on('candidates')->references('id')->cascadeOnDelete();
            $table->foreignId('social_account_id')->index();
            $table->foreign('social_account_id')->on('social_accounts')->references('id')->cascadeOnDelete();
            $table->string('link');
            $table->primary(['candidate_id', 'social_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_social_account');
    }
};
