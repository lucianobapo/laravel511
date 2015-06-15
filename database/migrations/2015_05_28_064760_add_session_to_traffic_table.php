<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSessionToTrafficTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('traffic', function(Blueprint $table)
		{
            $table->string('session_id')->nullable();
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
		Schema::table('traffic', function(Blueprint $table)
		{
            $table->dropColumn('session_id');
		});
        echo get_class($this)." is down\n";
	}

}
