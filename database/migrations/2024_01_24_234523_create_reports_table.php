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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('data_type')->nullable();
            $table->string('chart_type')->nullable();
            $table
                ->enum('permissions', ['P', 'G'])
                ->nullable()
                ->default(null);
            $table
                ->enum('statistics_mode', ['Y', 'N'])
                ->nullable()
                ->default(null);
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('data')->nullable();
            $table->longText('user_list')->nullable();
            $table->longText('group_list')->nullable();
            $table->longText('dropdowns')->nullable();
            $table->longText('fieldNames')->nullable();
            $table->longText('fieldStatisticsNames')->nullable();
            $table->longText('fieldIds')->nullable();
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
        Schema::dropIfExists('reports');
    }
};
