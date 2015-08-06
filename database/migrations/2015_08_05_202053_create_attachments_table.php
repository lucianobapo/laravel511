<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
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

            // Campos
            $table->string('file')->nullable();
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
        Schema::drop('attachments');
        echo get_class($this)." is down\n";
    }
}
