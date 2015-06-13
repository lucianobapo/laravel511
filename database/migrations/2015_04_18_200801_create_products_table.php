<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();

            $table->string('mandante')->index();

            $table->integer('uom_id')->unsigned()->index()->nullable();
            $table->foreign('uom_id')
                ->references('id')
                ->on('shared_unit_of_measures')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('cost_id')->unsigned()->index()->nullable();
            $table->foreign('cost_id')
                ->references('id')
                ->on('cost_allocates')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->string('nome');
            $table->string('imagem')->nullable();
            $table->string('cod_fiscal')->nullable();
            $table->string('cod_barra')->nullable();
            $table->boolean('promocao')->default(0);
            $table->boolean('estoque')->default(0);
            $table->float('valorUnitVenda')->nullable();
            $table->float('valorUnitVendaPromocao')->nullable();
            $table->float('valorUnitCompra')->nullable();
            //temporario
            $table->integer('old_id')->index()->nullable();
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
		Schema::drop('products');
        echo get_class($this)." is down\n";
	}

}
