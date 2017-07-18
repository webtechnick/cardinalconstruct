<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id')->unsigned()->index();
            $table->boolean('is_before')->index()->default(false); // is before photo
            $table->boolean('is_active')->index()->default(true);
            $table->string('name'); // name of file
            $table->text('caption')->nullable(); // caption of photo.
            $table->string('path'); // filepath
            $table->string('thumbnail_path'); // path to thumbnail
            $table->timestamps();

            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('photos');
    }
}
