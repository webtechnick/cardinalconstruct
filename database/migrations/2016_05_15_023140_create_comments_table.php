<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->unsigned()->nullable(); //User is not required
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('post_id')->index()->unsigned();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade'); // if post is deleted, delete it's comments
            $table->boolean('is_active')->index()->default(true);
            $table->text('body');
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
        Schema::drop('comments');
    }
}
