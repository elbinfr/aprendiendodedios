<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersiculoVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versiculo_version', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('versiculo_id');
            $table->integer('version_id');
            $table->text('texto');
            $table->timestamps();

            $table->foreign('versiculo_id')
                    ->references('id')->on('versiculo')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('version_id')
                    ->references('id')->on('version')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('versiculo_version');
    }
}
