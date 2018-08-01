<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match', function (Blueprint $table) {
            $table->increments('id');
            $table->string('match_number',10)->comment('比赛编号');
            $table->integer('competition_id')->default(0)->comment('赛事名称d');
            $table->integer('host_team_id')->default(0)->comment('主队id');
            $table->integer('guest_team_id')->default(0)->comment('客队id');
            $table->integer('rank1')->default(0)->comment('主队的排名');
            $table->integer('rank2')->default(0)->comment('客队的排名');
            $table->integer('host_team_score')->default(0)->comment('主队进球');
            $table->integer('guest_team_score')->default(0)->comment('客队进球');
            $table->timestamp('match_time')->nullable()->comment('比赛时间');
            $table->string('address')->nullable()->comment('比赛地点');
            $table->string('weather')->nullable()->comment('比赛天气');
            $table->tinyInteger('status')->default(0)->comment('比赛状态 0:未开赛，1:比赛中，2比赛结束');
            $table->float('current',4,2)->default(0)->comment('当前赔率');
            $table->tinyInteger('analogue_injection')->default(0)->comment('0：未投注，1：主胜 2：平，3负');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match');
    }
}
