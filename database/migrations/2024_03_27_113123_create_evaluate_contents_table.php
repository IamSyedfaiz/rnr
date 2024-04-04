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
        Schema::create('evaluate_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->nullable();
            $table->foreignId('Workflow_id')->constrained()->nullable();
            $table->foreignId('task_id')->constrained()->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('active')->nullable();
            $table->string('alias')->nullable();
            $table->string('type')->nullable();
            $table->string('advanced_operator_logic')->nullable();
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
        Schema::dropIfExists('evaluate_contents');
    }
};
