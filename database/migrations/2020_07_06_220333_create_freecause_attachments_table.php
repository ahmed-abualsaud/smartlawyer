<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreecauseAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freecause_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cause_id');
            $table->string('file');
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
        Schema::dropIfExists('freecause_attachments');
    }
}
