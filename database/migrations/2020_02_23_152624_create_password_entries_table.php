<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_entrie', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('uidO')->unsigned();
            $table->foreign("uid")->references('id')->on('users');
            $table->string("site",255);
            $table->string("passwordhash",2500);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_entries');
    }
}
