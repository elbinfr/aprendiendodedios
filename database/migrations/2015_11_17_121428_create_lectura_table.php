<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectura', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cronograma_id')->unsigned();
            $table->bigInteger('libro_id');
            $table->string('libro_nombre', 20);
            $table->integer('capitulo');
            $table->integer('inicio');
            $table->integer('final');
            $table->enum('estado', ['pendiente', 'finalizado'])->default('pendiente');
            $table->timestamps();

            $table->foreign('cronograma_id')
                    ->references('id')->on('cronograma')
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
        Schema::drop('lectura');
    }
}
