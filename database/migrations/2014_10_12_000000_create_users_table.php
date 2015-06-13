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
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('mandante')->index();



			$table->string('name');
			$table->string('avatar')->nullable();
//			$table->string('email')->unique();
			$table->string('password', 60)->nullable();

            $table->string('username')->nullable();
            $table->string('email')->unique()->default(time() . '-no-reply@'.config('app.domain'));
//            $table->string('email')->unique()->default(time() . '-no-reply@EasyAuthenticator.com')->change();
//            $table->string('avatar');
            $table->string('provider')->default('laravel');
            $table->string('provider_id')->unique()->nullable();
            $table->string('activation_code')->nullable();
            $table->integer('active')->nullable();

			$table->rememberToken();
			$table->timestamps();
            $table->softDeletes();
		});

        echo get_class($this)." is up\n";
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
        echo get_class($this)." is down\n";
	}

}
