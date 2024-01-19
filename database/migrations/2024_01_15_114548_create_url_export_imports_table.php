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
        Schema::create('url_export_imports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('excel_file');
            $table->time('start_time')->nullable();
            $table->date('start_day')->nullable();
            $table->string('recurring')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->integer('scheduled_day')->nullable();
            $table->string('selected_week_day')->nullable();
            $table->string('column_mappings')->nullable();
            $table->string('key_field')->nullable();
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('application_id');
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
        Schema::dropIfExists('url_export_imports');
    }
};
