<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hash_id')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('preview')->nullable();
            $table->longText('content')->nullable();
            $table->text('og_description')->nullable();
            $table->enum('status', ['draft', 'publish', 'review', 'unlist'])->default('draft');
            $table->integer('view_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->dateTime('publish_at')->nullable();

            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
            $table->unsignedBigInteger('series_id')->nullable();
            $table->foreign('series_id')->references('id')->on('series');
            $table->unsignedBigInteger('images_id');
            $table->foreign('images_id')->references('id')->on('images');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
