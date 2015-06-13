<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->softDeletes();

            $table->string('mandante')->index();

            /**
             * Relacionamentos entre as tabelas
             */
            $table->integer('partner_id')->unsigned()->index()->nullable();
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('address_id')->unsigned()->index()->nullable();
            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('currency_id')->unsigned()->index()->nullable();
            $table->foreign('currency_id')
                ->references('id')
                ->on('shared_currencies')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('type_id')->unsigned()->index()->nullable();
            $table->foreign('type_id')
                ->references('id')
                ->on('shared_order_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->integer('payment_id')->unsigned()->index()->nullable();
            $table->foreign('payment_id')
                ->references('id')
                ->on('shared_order_payments')
                ->onDelete('restrict')
                ->onUpdate('cascade');


            $table->timestamp('posted_at');
            $table->float('valor_total')->nullable();
            $table->float('desconto_total')->nullable();
            $table->float('troco')->nullable();

//            $table->enum('tipo_da_ordem', [
//                'orcamentoVenda',
//                'orcamentoServico',
//                'producao',
//                'venda',
//                'servico',
//                'compra',
//                'consumo'
//            ]);
//            $table->enum('tipo_do_pagamento', [
//                'pagseguro',
//                'fiado',
//                'vistad',
//                'vistacc',
//                'vistacd',
//                'parcelado'
//            ]);

            $table->text('descricao')->nullable();
            $table->string('referencia')->nullable();
            $table->string('obsevacao')->nullable();

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
		Schema::drop('orders');
        echo get_class($this)." is down\n";
	}

}
