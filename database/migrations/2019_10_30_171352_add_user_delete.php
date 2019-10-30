<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dbuser_access', function (Blueprint $table) {
            $table->boolean('delete_after_expired')->default($value = false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dbuser_access', function (Blueprint $table) {
            $table->dropColumn('delete_after_expired');
        });
    }
}
