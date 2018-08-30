<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWangyiHostHistoryScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wangyi_host_history_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id')->default(0)->comment('source表中的id');
            $table->integer('match_id')->default(0)->comment('比赛队伍,冗余字段');
            $table->integer('host_team_id')->default(0)->comment('主队id');
            $table->integer('guest_team_id')->default(0)->comment('客队id');
            $table->string('host_team_name')->nullable()->comment('交战主队名称');
            $table->string('guest_team_name')->nullable()->comment('客队名称');
            $table->tinyInteger('host_score')->default(0)->comment('主队进球数');
            $table->tinyInteger('guest_score')->default(0)->comment('客队进球数');
            $table->tinyInteger('is_host')->default(0)->comment('目标队伍的位置 0 左侧 1 右侧');
            $table->timestamp('match_time')->nullable()->comment('比赛时间');
            $table->string('league_name')->nullable()->comment('联盟名称');
            $table->integer('league_id')->default(0)->comment('联盟id,冗余字段');
            $table->tinyInteger('match_result')->default(0)->comment('比赛结果 1 胜 2 平 3 负');
            $table->tinyInteger('score')->default(0)->comment('进球数');
            $table->integer('aim_team_id')->default(0)->comment('目标队伍id');
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
        Schema::dropIfExists('wangyi_host_history_score');
    }
}
