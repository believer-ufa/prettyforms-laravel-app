<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        // Create a databse
        //DB::statement("CREATE DATABASE `prettyforms-testapp` COLLATE 'utf8_general_ci'");

		Schema::create('users', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
            $table->string('email',100)->unique()->index();
            $table->string('name',100);
            $table->string('password',100);
            $table->string('remember_token',100)->nullable();
			$table->timestamps();
		});

		Schema::create('comments', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('article_id')->unsigned();
            $table->text('text');
            $table->integer('rating')->default('0');
			$table->timestamps();
		});

		Schema::create('comments_rates', function(Blueprint $table)
		{
            $table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('comment_id')->unsigned();
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');

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
		Schema::drop('users');
	}

}
