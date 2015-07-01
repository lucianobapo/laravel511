<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostedAtToOrderConfirmationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_confirmations', function(Blueprint $table)
		{
            $table->timestamp('posted_at')->nullable();
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
		Schema::table('order_confirmations', function(Blueprint $table)
		{
            $table->dropColumn('posted_at');
		});
        echo get_class($this)." is down\n";
	}

}
