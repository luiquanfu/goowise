<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->text('name');
            $table->text('email');
            $table->text('mobile');
            $table->text('reason');
            $table->text('ip_address');
            $table->text('user_agent');
            $table->integer('email_at');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}
