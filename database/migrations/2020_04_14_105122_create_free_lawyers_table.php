<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreeLawyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_lawyers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('details')->nullable();
            $table->text('reply')->nullable();
            $table->integer('status')->default(0)->comment('0:open,1:closed');
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('lawyer_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('free_lawyers');
    }
}
