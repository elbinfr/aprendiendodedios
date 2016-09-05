<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersiculoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versiculo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('libro_id');
            $table->integer('numero_capitulo');
            $table->integer('numero_versiculo');
            $table->string('titulo', 150)->nullable();
            $table->text('texto');
            $table->timestamps();

            $table->foreign('libro_id')
                    ->references('id')->on('libro')
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
        Schema::drop('versiculo');
    }
}
