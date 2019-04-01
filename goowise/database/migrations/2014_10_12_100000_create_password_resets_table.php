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
            $table->string('name', 50);
            $table->string('email');
            $table->string('mobile', 15);
            $table->string('password');
            $table->string('last_visit');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('name');
            $table->index('email');
            $table->index('mobile');
            $table->index('deleted_at');
        });

        Schema::create('admin_tokens', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->bigInteger('admin_id');
            $table->string('ip_address', 25);
            $table->string('user_agent');
            $table->string('api_token', 32);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('admin_id');
            $table->index('api_token');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::drop('admins');
        Schema::drop('admin_tokens');
    }
}
