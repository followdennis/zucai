<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateAokeHistoryGuestScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aoke_history_guest_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match_id')->default(0)->comment('比赛id');
            $table->tinyInteger('score')->default(0)->comment('进球数据');
            $table->timestamp('score_time')->nullable()->comment('进球的比赛时间');
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
        Schema::dropIfExists('aoke_history_guest_score');
    }
}
