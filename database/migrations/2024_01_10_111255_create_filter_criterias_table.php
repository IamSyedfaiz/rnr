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
        Schema::create('filter_criterias', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('notification_id')
                ->constrained()
                ->nullable();
            $table
                ->foreignId('field_id')
                ->constrained()
                ->nullable();
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
        Schema::dropIfExists('filter_criterias');
    }
};
