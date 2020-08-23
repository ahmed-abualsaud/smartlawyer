<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJudicialHearingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('judicial_hearings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cause_id');
            $table->dateTime('date');
            $table->text('description')->nullable();
            $table->text('results_text')->nullable();
            $table->foreign('cause_id')->references('id')->on('causes')->onDelete('cascade');
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
        Schema::dropIfExists('judicial_hearings');
    }
}
