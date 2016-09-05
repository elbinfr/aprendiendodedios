<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClaseToLibroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('libro', function (Blueprint $table) {
            $table->enum('clase', ['pentateuco', 'historia', 'poesia', 'profeta', 'evangelio', 'epistola', 'profecia'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('libro', function (Blueprint $table) {
            //
        });
    }
}
