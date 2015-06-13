<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharedStatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shared_stats', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();

            $table->string('status')->index();
            $table->string('descricao')->nullable();
		});

        Schema::create('product_shared_stat',function(Blueprint $table){
            $table->timestamps();

            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')
                ->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('shared_stat_id')->unsigned()->index();
            $table->foreign('shared_stat_id')->references('id')
                ->on('shared_stats')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::create('cost_allocate_shared_stat',function(Blueprint $table){
            $table->timestamps();

            $table->integer('cost_allocate_id')->unsigned()->index();
            $table->foreign('cost_allocate_id')->references('id')
                ->on('cost_allocates')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')
                ->on('shared_stats')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::create('partner_shared_stat',function(Blueprint $table){
            $table->timestamps();

            $table->integer('partner_id')->unsigned()->index();
            $table->foreign('partner_id')->references('id')
                ->on('partners')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('shared_stat_id')->unsigned()->index();
            $table->foreign('shared_stat_id')->references('id')
                ->on('shared_stats')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::create('order_shared_stat',function(Blueprint $table){
            $table->timestamps();

            $table->integer('order_id')->unsigned()->index();
            $table->foreign('order_id')->references('id')
                ->on('orders')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('shared_stat_id')->unsigned()->index();
            $table->foreign('shared_stat_id')->references('id')
                ->on('shared_stats')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::create('item_order_shared_stat',function(Blueprint $table){
            $table->timestamps();

            $table->integer('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')
                ->on('item_orders')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('status_id')->unsigned()->index();
            $table->foreign('status_id')->references('id')
                ->on('shared_stats')
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
		Schema::drop('product_shared_stat');
		Schema::drop('cost_allocate_shared_stat');
		Schema::drop('partner_shared_stat');
		Schema::drop('order_shared_stat');
		Schema::drop('item_order_shared_stat');
		Schema::drop('shared_stats');
        echo get_class($this)." is down\n";
	}

}
