<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbuseraccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dbuser_access', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->string('user');
            $table->string('host');
            $table->dateTime('expire')->nullable($value = true);
            $table->boolean('expired')->default($value = false);
            $table->timestamps();
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');
            $table->unique('server_id', 'user', 'host');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dbuser_access');
    }
}
