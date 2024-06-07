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
        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('type')->nullable();
            $table->enum('active', ['Y', 'N'])->nullable();
            $table->string('description')->nullable();
            $table->string('layout')->nullable();
            $table->string('report_id')->nullable();
            $table->enum('access', ['PR', 'PB'])->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('user_list')->default(0);
            $table->longText('group_list')->default(0);
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
        Schema::dropIfExists('dashboards');
    }
};
