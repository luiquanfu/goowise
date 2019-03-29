<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->text('name');
            $table->text('mobile');
            $table->text('email');
            $table->text('password');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
        });

        Schema::create('admin_tokens', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->bigInteger('admin_id');
            $table->text('ip_address');
            $table->text('user_agent');
            $table->text('api_token');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
        });
    }

    public function down()
    {
        Schema::drop('admins');
        Schema::drop('admin_tokens');
    }
}
