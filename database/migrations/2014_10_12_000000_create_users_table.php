<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hash_id')->unique();
            $table->string('socialite_id');
            $table->string('socialite_provider');
            $table->string('username')->unique();
            $table->string('email');
            $table->string('avatar');
            $table->enum('role', ['admin', 'author', 'editor', 'user'])->default('user');
            $table->rememberToken();

            $table->unique(['socialite_id', 'socialite_provider', 'email']);

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
        Schema::dropIfExists('users');
    }
}
