<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->text('notes')->nullable();
            $table->integer('bound_id')->unsigned();
            $table->timestamps();

            $table->foreign('bound_id')->references('id')->on('category_bounds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses', function (Blueprint $table) {
            $table->dropForeign('expenses_bound_id_foreign');
        });
    }
}
