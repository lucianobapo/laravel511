<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_confirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            /**
             * Mandante do Banco de dados
             */
            $table->string('mandante')->index();

            /**
             * Relacionamentos entre as tabelas
             */
            $table->integer('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->enum('type', [
                'recebido',
                'producao',
                'pronto',
                'entregando',
                'entregue',
                'pago',
            ]);
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
        Schema::drop('order_confirmations');
        echo get_class($this)." is down\n";
    }
}
