<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->string('id', 20);
            $table->string('name', 50);
            $table->string('email');
            $table->string('mobile', 15);
            $table->string('reason');
            $table->string('ip_address', 25);
            $table->string('user_agent');
            $table->integer('email_at');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('name');
            $table->index('email');
            $table->index('mobile');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::drop('users');
    }
}