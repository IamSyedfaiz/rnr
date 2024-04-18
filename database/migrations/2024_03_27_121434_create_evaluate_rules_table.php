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
        Schema::create('evaluate_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluate_content_id')->nullable()->constrained('evaluate_contents');
            $table->foreignId('field_id')->nullable()->constrained('fields');
            $table->string('filter_operator')->nullable();
            $table->string('filter_value')->nullable();
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
        Schema::dropIfExists('evaluate_rules');
    }
};
