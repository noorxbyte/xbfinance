<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date');
            $table->integer('account_from')->unsigned()->nullable();
            $table->foreign('account_from')->references('id')->on('accounts')->onDelete('set null');
            $table->integer('account_to')->unsigned()->nullable();
            $table->foreign('account_to')->references('id')->on('accounts')->onDelete('set null');
            $table->decimal('amount', 64, 2)->unsigned();
            $table->string('comment')->nullable();
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
        Schema::drop('transfers');
    }
}
