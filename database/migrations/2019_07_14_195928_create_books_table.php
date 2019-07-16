<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->text('description');
            $table->string('author',150)->nullable(); //aceita valor vazio
            $table->date('release_date')->nullable(); //aceita valor vazio
            $table->integer('user_id')->unsigned(); //so pode colocar valor positivo
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //chave estrangeira q pega o id como referencia da tabela o ussers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
