<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('name', 40);
            $table->string('description', 255);
        });

        Schema::table('users', function(Blueprint $table)
        {
            $table->integer('role_id')->unsigned()->index()->nullable();
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
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
        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints

        if (Schema::hasTable('roles')) Schema::drop('roles');
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn('role_id');
        });

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
        echo get_class($this)." is down\n";
    }
}
