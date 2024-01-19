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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->longText('fields')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('access')->nullable();
            $table->longText('groups')->nullable();
            $table->integer('status')->nullable();
            $table->integer('updated_by')->nullable();
            $table->longText('description')->nullable();
            $table->longText('attachments')->nullable();
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
        Schema::dropIfExists('applications');
    }
};
