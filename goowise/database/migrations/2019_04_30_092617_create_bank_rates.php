<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankRates extends Migration
{
    public function up()
    {
        Schema::create('rates', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->string('name', 30);
            $table->double('interest');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');
            
            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('banks', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->string('name', 30);
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('bank_loans', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->bigInteger('bank_id');
            $table->string('name', 30);
            $table->integer('lock_period');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('bank_id');
            $table->index('name');
            $table->index('deleted_at');
        });

        Schema::create('bank_rates', function (Blueprint $table)
        {
            $table->bigInteger('id');
            $table->bigInteger('rate_id');
            $table->bigInteger('bank_id');
            $table->bigInteger('bank_loan_id');
            $table->integer('year');
            $table->enum('calculate', ['add', 'subtract']);
            $table->double('interest');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at');

            $table->primary('id');
            $table->index('rate_id');
            $table->index('bank_id');
            $table->index('bank_loan_id');
            $table->index('deleted_at');
        });
    }

    public function down()
    {
        Schema::drop('banks');
        Schema::drop('rates');
        Schema::drop('bank_loans');
        Schema::drop('bank_rates');
    }
}
