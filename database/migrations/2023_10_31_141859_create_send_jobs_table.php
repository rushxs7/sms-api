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
        Schema::create('send_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('instant');
            $table->boolean('bulk')->default(false);
            $table->string('message');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('job_finished_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('send_jobs');
    }
};
