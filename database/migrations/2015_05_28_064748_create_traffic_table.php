<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrafficTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('traffic', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('remote_address',15);
            $table->json('user_info');
            $table->json('server_info');
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
		Schema::drop('traffic');
        echo get_class($this)." is down\n";
	}

}
