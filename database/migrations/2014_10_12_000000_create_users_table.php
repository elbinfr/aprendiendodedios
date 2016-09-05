<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->enum('sexo', ['Hombre', 'Mujer']);
            $table->integer('pais_id');
            $table->enum('estado', ['Activo', 'Inactivo'])->nullable();
            $table->enum('esta_logueado', ['Si', 'No'])->nullable();
            $table->integer('perfil_id');
            $table->string('celular', 20)->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('registration_token')->nullable();
            $table->rememberToken();
            $table->timestamps();

            //
            $table->foreign('pais_id')
                    ->references('id')->on('pais')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('perfil_id')
                    ->references('id')->on('perfil')
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
        Schema::drop('users');
    }
}
