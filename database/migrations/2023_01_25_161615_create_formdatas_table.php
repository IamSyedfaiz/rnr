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
        Schema::create('formdatas', function (Blueprint $table) {
            $table->id();
            $table->integer('userid')->nullable();
            $table->integer('application_id')->nullable();
            $table->longtext('data')->nullable();
            $table->longtext('type123')->nullable();
            $table->string('extra')->nullable();
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
        Schema::dropIfExists('formdatas');
    }
};
