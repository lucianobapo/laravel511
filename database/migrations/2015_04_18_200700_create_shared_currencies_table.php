<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shared_currencies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();

            $table->string('nome_universal', 3);
            $table->string('descricao');
		});

        echo get_class($this)." is up\n";

//        Schema::create('orders_has_shared_currencies',function(Blueprint $table){
//            $table->timestamps();
//
//            $table->integer('order_id')->unsigned()->index();
//            $table->foreign('order_id')->references('id')
//                ->on('orders')
//                ->onDelete('restrict')
//                ->onUpdate('cascade');
//
//            $table->integer('currency_id')->unsigned()->index();
//            $table->foreign('currency_id')->references('id')
//                ->on('shared_currencies')
//                ->onDelete('restrict')
//                ->onUpdate('cascade');
//        });

//        Schema::create('item_orders_has_shared_currencies',function(Blueprint $table){
//            $table->timestamps();
//
//            $table->integer('item_id')->unsigned()->index();
//            $table->foreign('item_id')->references('id')
//                ->on('item_orders')
//                ->onDelete('restrict')
//                ->onUpdate('cascade');
//
//            $table->integer('currency_id')->unsigned()->index();
//            $table->foreign('currency_id')->references('id')
//                ->on('shared_currencies')
//                ->onDelete('restrict')
//                ->onUpdate('cascade');
//        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
//        Schema::drop('orders_has_shared_currencies');
//        Schema::drop('item_orders_has_shared_currencies');
        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Schema::drop('shared_currencies');
        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
        echo get_class($this)." is down\n";
	}

}
