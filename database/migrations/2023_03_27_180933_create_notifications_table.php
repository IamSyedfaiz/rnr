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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table
                ->enum('type', ['SRD', 'SN', 'ODNT'])
                ->nullable()
                ->default(null);
            $table
                ->enum('active', ['Y', 'N'])
                ->nullable()
                ->default(null);
            $table->longText('subject')->nullable();
            $table->longText('body')->nullable();
            $table->string('recurring')->nullable();
            $table->string('scheduled_time')->nullable();
            $table->string('scheduled_day')->nullable();
            $table->string('selected_week_day')->nullable();
            $table->longText('group_list')->default(0);
            $table->longText('user_list')->default(0);
            $table->string('advanced_operator_logic')->nullable();
            $table->string('user_cc')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('application_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('notifications');
    }
};
