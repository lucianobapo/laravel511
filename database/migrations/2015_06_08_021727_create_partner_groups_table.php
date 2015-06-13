<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_groups', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();

            $table->string('mandante')->index();

            $table->string('grupo');
		});

        Schema::create('partner_partner_group',function(Blueprint $table){
            $table->timestamps();

            $table->integer('partner_id')->unsigned()->index();
            $table->foreign('partner_id')->references('id')
                ->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('partner_group_id')->unsigned()->index();
            $table->foreign('partner_group_id')->references('id')
                ->on('product_groups')
                ->onDelete('restrict')
                ->onUpdate('cascade');
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
		Schema::drop('partner_groups');
		Schema::drop('partner_partner_group');
        echo get_class($this)." is down\n";
	}

}
