<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticuloComentarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulo_comentario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('articulo_id');
            $table->bigInteger('user_id');
            $table->text('contenido');
            $table->enum('swt_confirma', ['Si', 'No'])->default('No');
            $table->timestamps();

            $table->foreign('articulo_id')
                    ->references('id')->on('articulo')
                    ->onDelete('cascade');

            $table->foreign('user_id')
                    ->references('id')->on('users')
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
        Schema::drop('articulo_comentario');
    }
}
