<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterComplaintsRepliesTableAddStatusCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('complaint_replies', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('user_id')
                ->comment('0:unseen,1:seen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('complaint_replies', function (Blueprint $table) {
            $table->dropColumn(['status']);
        });
    }
}
