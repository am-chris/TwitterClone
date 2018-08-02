<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reporter_id')->unsigned();
            $table->integer('reported_id')->unsigned();
            $table->string('type');
            $table->string('reason');
            $table->text('description')->nullable();
            $table->string('action')->nullable();
            $table->string('duration')->nullable();
            $table->timestamps();

            $table->foreign('reporter_id')->references('id')->on('users');
            $table->foreign('reported_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
