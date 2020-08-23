<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCausesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->date('judgment_date')->nullable();
            $table->text('judgment_text')->nullable();
            $table->string('court_name')->nullable();
            $table->string('judicial_chamber')->nullable();
            $table->string('consideration_text')->nullable();
            $table->string('type')->nullable()->comment('new-stab-veto-Seek');
            $table->integer('is_public')->default(1)->comment('0:private,1:public');
            $table->integer('status')->default(0)->comment('0:pending,1:in progress,2:complete');
            $table->string('related_cause_number')->nullable();
            $table->string('lawyer')->nullable();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('causes');
    }
}
