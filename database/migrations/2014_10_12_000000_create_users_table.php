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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('custom_userid')->nullable();
            $table->string('name')->nullable();
            $table->string('lastname')->nullable();
            $table->longText('group_id')->nullable();
            $table->integer('status')->default(0);
            $table->string('mobile_no')->nullable();
            $table->string('email')->unique();
            $table->string('ssh_key')->nullable();
            $table->string('token')->nullable();
            $table->string('role')->nullable();
            $table->longText('remarks')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
