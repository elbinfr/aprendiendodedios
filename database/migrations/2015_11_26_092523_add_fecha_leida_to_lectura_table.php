<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFechaLeidaToLecturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lectura', function (Blueprint $table) {
            $table->date('fecha_leida')->nullable();
            $table->enum('calificacion', ['atrasado', 'correcto', 'adelantado'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lectura', function (Blueprint $table) {
            //
        });
    }
}
