<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostAllocatesIndexes extends Migration
{

    private $table = 'cost_allocates';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.migration'))->table($this->table, function(Blueprint $table)
        {
            $table->index('deleted_at', $this->table.'_deleted_at_index');
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
        Schema::connection(config('database.migration'))->table($this->table, function(Blueprint $table)
        {
            $table->dropIndex($this->table.'_deleted_at_index');
        });
        echo get_class($this)." is down\n";
    }
}
