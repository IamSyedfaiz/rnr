<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->nullable();
            $table->foreignId('Workflow_id')->constrained()->nullable();
            $table->foreignId('task_id')->constrained()->nullable();
            $table->string('name')->nullable();
            $table->json('data')->nullable();
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
        Schema::dropIfExists('update_contents');
    }
};
